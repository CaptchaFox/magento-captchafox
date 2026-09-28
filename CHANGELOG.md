## 1.3.0

- Fixed the login widget of the checkout email step: widgets are bound to their own element
  instead of being looked up in the page, which could swap them with the authentication modal
  widget and left the email step without a widget.
- The checkout email step widget is reset after a failed login, the retry no longer sends an
  already used response.
- Fixed the widget overlapping the checkout shipping form on Magento 2.4.8 and later.
- The error message is shown when the widget could not be rendered.
- Rejected requests are stopped through Magento's action flag instead of `exit()`.
  `Observer\Validate` and `Observer\Validate\Frontend` take a new `ActionFlag` constructor
  argument, update classes extending them.
- Declared the Magento modules the extension depends on.

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
