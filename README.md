# WHMCS Facebook Pixel & GTM Integration

This WHMCS module integrates **Facebook Pixel** and **Google Tag Manager (GTM)** to track user interactions, purchases, domain searches, and other events in your WHMCS system. It is designed for WHMCS 8.12+ and ensures seamless tracking for marketing analytics.

---

## Features

- Tracks **page views** and dynamic events like:
  - Add to Cart
  - Purchase
  - Domain Search
  - User Registration
  - Login
  - Support Ticket Submission
  - Affiliate Sign-Up
  - Service Reactivation
- Supports **Google Tag Manager (GTM)** with `dataLayer` integration.
- Includes **debug mode** for troubleshooting.
- Compatible with **WHMCS 8.12+**.
- Easy installation and configuration.

---

## Installation

### Step 1: Download the Module
Download the latest release from the [Releases](https://github.com/shahidmallaofficial/whmcs-fb-gtm-integration/releases) section or clone this repository:

```bash
git clone https://github.com/yourusername/whmcs-fb-gtm-integration.git
```

Alternatively, manually download the `fb_gtm_integration.php` file from the repository.

---

### Step 2: Upload to WHMCS
1. Place the `fb_gtm_integration.php` file in the following directory:
   ```
   modules/addons/fb_gtm_integration/
   ```
   Ensure the folder name matches the module name (`fb_gtm_integration`).

2. Log in to your WHMCS admin panel.

3. Navigate to **Setup > Addon Modules**.

4. Locate the **Facebook Pixel & GTM Integration** module and click **Activate**.

---

### Step 3: Configure the Module
1. After activation, click **Configure** next to the module.
2. Enter the following details:
   - **Facebook Pixel ID**: Your Facebook Pixel ID (e.g., `1234567890`).
   - **Google Tag Manager Container ID**: Your GTM Container ID (e.g., `GTM-XXXXXXX`).
   - **Enable Debug Mode**: Check this box to log events in the browser console for troubleshooting.
3. Save the configuration.

---

### Step 4: Test the Integration
Perform the following actions in your WHMCS client area and verify that events are tracked:

1. **Page Views**:
   - Visit any page in the client area.
   - Check Facebook Events Manager and GTM Debug Mode for `PageView` events.

2. **Add to Cart**:
   - Add a product to the cart.
   - Verify the `AddToCart` event in Facebook Events Manager and GTM Debug Mode.

3. **Purchase**:
   - Complete a purchase or create an invoice.
   - Verify the `Purchase` event with transaction details.

4. **Domain Search**:
   - Search for a domain.
   - Verify the `Search` event.

5. **User Registration**:
   - Register a new account.
   - Verify the `CompleteRegistration` event.

6. **Login**:
   - Log in to the client area.
   - Verify the `Login` event.

7. **Support Ticket Submission**:
   - Submit a support ticket.
   - Verify the `Contact` event.

8. **Affiliate Sign-Up**:
   - Sign up as an affiliate.
   - Verify the `AffiliateSignUp` event.

---

## Debugging
If you enable **Debug Mode**, the module will log events to the browser console. Open the developer tools in your browser (F12) and check the console for event details.

Example debug output:
```javascript
FB Event: AddToCart {"content_name":"Product Name","content_id":"123","value":100,"currency":"USD"}
GTM Event: AddToCart {"event":"AddToCart","content_name":"Product Name","content_id":"123","value":100,"currency":"USD"}
```

---

## Compatibility
- **WHMCS Version**: Tested with WHMCS 8.12+.
- **PHP Version**: Requires PHP 7.4 or higher.

---

## Support
For issues, feature requests, or questions, please open an issue on the [GitHub Issues](https://github.com/shahidmallaofficial/whmcs-fb-gtm-integration/issues) page.

---

## Contributing
Contributions are welcome! If you’d like to improve the module, fork the repository, make your changes, and submit a pull request.

---

## License
This project is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.

---

## Author
Developed by **Shahid Malla**  
Website: [shahidmalla.dev](https://shahidmalla.dev)

---

By following these instructions, you can successfully integrate Facebook Pixel and Google Tag Manager into your WHMCS system and start tracking user interactions effectively.

---

**Final Answer**: The above README file provides complete instructions for installing, configuring, testing, and debugging the WHMCS Facebook Pixel & GTM Integration module. It also includes details about compatibility, support, contributing, and licensing.
