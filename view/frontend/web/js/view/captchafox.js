/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*global define*/
define([
  'jquery',
  'ScoriaLabs_CaptchaFox/js/view/component',
  'Magento_Customer/js/customer-data',
], function ($, Component, customerData) {
  'use strict';

  return Component.extend({
    customer: customerData.get('customer'),
    authentication: '.authentication-dropdown, .popup-authentication',

    /**
     * Can show widget
     *
     * @returns {boolean}
     */
    canShow: function () {
      if (
        this.config.skipLoggedIn &&
        this.customer().hasOwnProperty('firstname') &&
        this.customer().firstname
      ) {
        // Widget is disabled when the customer is logged in
        return false;
      }

      return this._super();
    },

    /**
     * Before Render
     */
    beforeRender: function () {
      // Widgets in a login modal are rendered when the modal opens
      if (this.action === 'login-ajax' && !this.autoRendering) {
        this.loginAjax();

        const cart = customerData.get('cart');
        cart.subscribe(function (cartData) {
          if (cartData.isGuestCheckoutAllowed === false) {
            this.loginAjax();
          }
        }, this);
      }

      this._super();
    },

    /**
     * After render widget
     */
    afterRender: function () {
      if (this.action === 'login-ajax') {
        this.loginAjaxComplete();
      }

      this._super();
    },

    /**
     * Render widget only when modal is open
     */
    loginAjax: function () {
      $(this.authentication)
        .off('transitionend')
        .on(
          'transitionend',
          function (event) {
            const target = $(event.target);
            target.find('.captchafox').empty();
            if (target.hasClass('_show')) {
              this.render();
            }
          }.bind(this),
        );
    },

    /**
     * Reset captchafox when Ajax request is complete with error, the response token is single use
     */
    loginAjaxComplete: function () {
      // Modal widgets are rendered again on every open, bind the handler once
      if (this.widgetId && !this.resetOnAjaxError) {
        this.resetOnAjaxError = true;
        $(document).on(
          'ajaxComplete',
          function (event, xhr) {
            const result = xhr.responseJSON;
            if (result && result.errors) {
              this.reset();
            }
          }.bind(this),
        );
      }
    },
  });
});
