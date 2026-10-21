# leads-to-rd

WordPress plugin that automatically syncs Contact Form 7 and WPForms submissions to [RD Station CRM](https://www.rdstation.com/). Zero configuration after setup.

## How it works

Once the API token is saved, every form submission is intercepted and sent to RD Station as a conversion event. No manual field mapping needed: the plugin detects email, name, and phone fields automatically.

```
Form submitted
  → Plugin detects email / name / phone
  → POST to RD Station Conversions API
  → Result logged to database
```

## Features

- Supports **Contact Form 7** and **WPForms** out of the box
- **Zero field mapping**: automatically detects email, name and phone by field name/label
- **Conversion logging**: every submission stored in the database with HTTP status and API response
- Lightweight, no production dependencies, pure WordPress APIs

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Contact Form 7 or WPForms (at least one)
- Active RD Station account with API access

## Installation

1. Download or clone this repository into your `wp-content/plugins/` folder:

```bash
cd wp-content/plugins/
git clone https://github.com/larissa4p/leads-to-rd.git
```

2. Activate the plugin in **WordPress Admin → Plugins**

## Configuration

1. Go to **Settings → RD Station** in the WordPress admin panel
2. Paste your API token (found in **RD Station → Integrações → API Token**)
3. Save. All new form submissions will sync automatically.

## Supported field names

The plugin uses fuzzy matching to find the right fields:

| Data   | Detected field names                                          |
|--------|---------------------------------------------------------------|
| Email  | `email`, `your-email`, `e-mail`, `email-address`             |
| Name   | `name`, `your-name`, `nome`, `full-name`, `fullname`         |
| Phone  | `phone`, `tel`, `telefone`, `your-phone`, `celular`, `mobile`|

## Conversion logs

All submissions are logged to a `{prefix}_rdstation_log` table with:

- Form name
- Contact email
- HTTP status from RD Station API
- Success flag
- Full response body
- Timestamp

## Tech stack

PHP · WordPress · Contact Form 7 · WPForms · RD Station API · PHPUnit

## License

[GPL-2.0+](https://www.gnu.org/licenses/gpl-2.0.html)
