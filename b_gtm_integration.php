<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly.");
}

/**
 * Module Configuration
 */
function fb_gtm_integration_config() {
    return [
        "name" => "Facebook Pixel & GTM Integration",
        "description" => "Advanced tracking for Facebook Pixel and Google Tag Manager with full WHMCS event support.",
        "version" => "2.0",
        "author" => "Shahid Malla <a href='https://shahidmalla.dev' target='_blank'>shahidmalla.dev</a>",
        "fields" => [
            "fb_pixel_id" => [
                "FriendlyName" => "Facebook Pixel ID",
                "Type" => "text",
                "Size" => "25",
                "Description" => "Enter your Facebook Pixel ID (e.g., 1234567890).",
            ],
            "gtm_container_id" => [
                "FriendlyName" => "Google Tag Manager ID",
                "Type" => "text",
                "Size" => "25",
                "Description" => "Enter your GTM Container ID (e.g., GTM-XXXXXXX).",
            ],
            "enable_debug" => [
                "FriendlyName" => "Enable Debug Mode",
                "Type" => "yesno",
                "Description" => "Enable debug logging for troubleshooting.",
            ],
        ],
    ];
}

/**
 * Module Activation
 */
function fb_gtm_integration_activate() {
    return [
        'status' => 'success',
        'description' => 'Module activated. Configure settings in Addons > Facebook Pixel & GTM.',
    ];
}

/**
 * Module Deactivation
 */
function fb_gtm_integration_deactivate() {
    return [
        'status' => 'success',
        'description' => 'Module deactivated successfully.',
    ];
}

/**
 * Client-Side Scripts (Facebook Pixel + GTM)
 */
add_hook('ClientAreaHeadOutput', 1, function ($vars) {
    $settings = getModuleSettings();
    $output = '';

    // Google Tag Manager (NoScript Fallback)
    if (!empty($settings['gtm_container_id'])) {
        $output .= <<<HTML
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$settings['gtm_container_id']}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
HTML;
    }

    // Facebook Pixel + GTM Initialization
    if (!empty($settings['fb_pixel_id']) || !empty($settings['gtm_container_id'])) {
        $output .= <<<HTML
<script>
  // Facebook Pixel Initialization
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
  document,'script','https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '{$settings['fb_pixel_id']}');
  fbq('track', 'PageView');

  // GTM Data Layer
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{$settings['gtm_container_id']}');
</script>
HTML;
    }

    return $output;
});

/**
 * Event Tracking Hooks
 */
add_hook('ClientAdd', 1, function ($vars) {
    trackEvent('CompleteRegistration', [
        'user_id' => $vars['userid'],
        'email' => $vars['email'],
    ]);
});

add_hook('ClientLogin', 1, function ($vars) {
    trackEvent('Login', [
        'user_id' => $vars['userid'],
        'email' => $vars['email'],
    ]);
});

add_hook('ShoppingCartAddItem', 1, function ($vars) {
    $product = $vars['productinfo'];
    trackEvent('AddToCart', [
        'content_name' => $product['name'],
        'content_id' => $product['pid'],
        'content_type' => 'product',
        'value' => $product['price'],
        'currency' => $product['currency'],
    ]);
});

add_hook('DomainSearchCompleted', 1, function ($vars) {
    trackEvent('Search', [
        'search_term' => $vars['searchTerm'],
        'results_count' => count($vars['results']),
    ]);
});

add_hook('InvoicePaid', 1, function ($vars) {
    $invoice = $vars['invoice'];
    trackEvent('Purchase', [
        'transaction_id' => $invoice->id,
        'value' => $invoice->total,
        'currency' => $invoice->currencycode,
        'contents' => getInvoiceItemsForTracking($invoice->id),
    ]);
});

add_hook('TicketOpen', 1, function ($vars) {
    trackEvent('Contact', [
        'subject' => $vars['subject'],
        'department' => $vars['department'],
    ]);
});

add_hook('AffiliateActivate', 1, function ($vars) {
    trackEvent('AffiliateSignUp', [
        'user_id' => $vars['affiliateid'],
        'commission_rate' => $vars['commissionrate'],
    ]);
});

add_hook('ServiceUnsuspension', 1, function ($vars) {
    trackEvent('ServiceReactivation', [
        'service_id' => $vars['serviceid'],
        'product_name' => $vars['product']['name'],
    ]);
});

/**
 * Helper Functions
 */
function trackEvent($eventName, $data = []) {
    $settings = getModuleSettings();
    $debug = $settings['enable_debug'] ?? false;

    if (!empty($settings['fb_pixel_id'])) {
        $fbData = json_encode($data);
        $debugCode = $debug ? "console.log('FB Event: $eventName', $fbData);" : '';
        echo <<<HTML
<script>
  $debugCode
  fbq('track', '$eventName', $fbData);
</script>
HTML;
    }

    if (!empty($settings['gtm_container_id'])) {
        $gtmData = json_encode(['event' => $eventName] + $data);
        $debugCode = $debug ? "console.log('GTM Event: $eventName', $gtmData);" : '';
        echo <<<HTML
<script>
  $debugCode
  dataLayer.push($gtmData);
</script>
HTML;
    }
}

function getInvoiceItemsForTracking($invoiceId) {
    $items = [];
    $result = select_query('tblinvoiceitems', 'description, amount', ['invoiceid' => $invoiceId]);
    while ($item = mysql_fetch_assoc($result)) {
        $items[] = [
            'name' => $item['description'],
            'price' => $item['amount'],
        ];
    }
    return $items;
}

function getModuleSettings() {
    static $settings;
    if (!$settings) {
        $settings = [];
        $query = Capsule::table('tbladdonmodules')
            ->where('module', 'fb_gtm_integration')
            ->get();
        foreach ($query as $row) {
            $settings[$row->setting] = $row->value;
        }
    }
    return $settings;
}
