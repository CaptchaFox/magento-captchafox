## 1.2.1

- Fixed the widget missing on composer installations. The module was registered with a
  `vendor/composer/../` path, so Magento rejected its templates ("Invalid template file") and
  rendered no CaptchaFox block in production mode, while the forms were still validated.

## 1.2.0

- Storefront validation is no longer skipped for logged in customers by default. The previous
  behaviour is available under `Storefront > Skip validation for logged in customers`.
- Switched to observing magento form events for the backend verification

## 1.1.0

- Update CSP Policies

## 1.0.0

- Initial release
