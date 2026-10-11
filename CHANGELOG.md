## 1.3.0

- Fixed: the checkout email step could lose its login widget to the authentication popup.
- Fixed: the checkout email step widget is reset after a failed login.
- Fixed: the widget overlapped the checkout shipping form on Magento 2.4.8 and later.
- Fixed: render errors of the widget are shown.
- Changed: rejected requests are stopped through the action flag instead of `exit()`.
  `Observer\Validate` and `Observer\Validate\Frontend` take an optional last constructor argument
  `ActionFlag`.
- Changed: the language source model implements `OptionSourceInterface`; `toOptionArray()` returns `array`.
- Changed: the module declares the Magento modules it depends on.
- Removed: the internal marker `data-captchafox-initialized`.
- Deprecated: `isFormEnabled()` of the frontend and admin observers is available again (removed in
  1.2.0); use `getEnabledForms()`.

Upgrading from 1.1.x: since 1.2.0 every form has its own observer (`captchafox_validate_<form>`).
Disabling `captchafox_frontend_server_validation` or `captchafox_admin_server_validation` has no effect;
exclude forms in the configuration instead.

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
