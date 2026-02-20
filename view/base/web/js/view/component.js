/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*global define*/
define([
  'ko',
  'jquery',
  'uiComponent',
  'captchaFox',
  'mage/translate',
], function (ko, $, Component) {
  'use strict';

  return Component.extend({
    defaults: {
      template: 'ScoriaLabs_CaptchaFox/captchafox',
    },
    configSource: 'captchafoxConfig',
    config: {
      enabled: false,
      sitekey: '',
      forms: [],
      mode: 'normal',
      theme: 'auto',
    },
    action: 'default',
    mode: '', // Override config value if not empty
    theme: '', // Override config value if not empty,
    containerId: '',
    widgetId: null,
    autoRendering: true,
    element: null,
    maxInitAttempts: 40,

    /**
     * Initialize
     */
    initialize: function () {
      this._super();

      if (
        typeof window[this.configSource] !== 'undefined' &&
        window[this.configSource].config
      ) {
        this.config = window[this.configSource].config;
      }

      if (this.config.theme === 'auto') {
        this.config.theme = 'light';
      }

      this.initWidget();

      return this;
    },

    /**
     * Can show widget
     *
     * @returns {boolean}
     */
    canShow: function () {
      return this.config.enabled && this.config.forms.indexOf(this.action) >= 0;
    },

    /**
     * Load widget
     *
     * @param {object} element
     */
    load: function (element) {
      this.element = element;
      this.element.setAttribute('data-captchafox-initialized', '1');

      if (!this.config.sitekey) {
        this.element.innerText = $.mage.__(
          'Unable to secure the form. The site key is missing.',
        );
      } else {
        this.beforeRender();
        if (this.autoRendering) {
          this.render();
        }
      }
    },

    /**
     * Initialize widget element and render when available
     *
     * @param {number} attempt
     */
    initWidget: function (attempt) {
      const tryCount = attempt || 0;

      if (!this.canShow() || this.element) {
        return;
      }

      const element = this.getElement();

      if (element) {
        this.load(element);
        return;
      }

      if (tryCount < this.maxInitAttempts) {
        window.setTimeout(this.initWidget.bind(this, tryCount + 1), 50);
      }
    },

    /**
     * Resolve widget element for current component instance
     *
     * @returns {HTMLElement|null}
     */
    getElement: function () {
      if (this.containerId) {
        return document.querySelector('#' + this.containerId + ' .captchafox');
      }

      return document.querySelector(
        '.captchafox[data-captchafox-action="' +
          this.action +
          '"]:not([data-captchafox-initialized="1"])',
      );
    },

    /**
     * Render widget
     */
    render: async function () {
      if (this.element) {
        const widgetId = await captchafox.render(this.element, {
          sitekey: this.config.sitekey,
          theme: this.theme || this.config.theme,
          mode: this.mode || this.config.mode,
          action: this.action,
          lang: this.config.lang === 'auto' ? null : this.config.lang,
        });
        if (typeof widgetId === 'undefined') {
          this.element.innerText = $.mage.__('Unable to secure the form');
        } else {
          this.widgetId = widgetId;
        }
        this.afterRender();
      }
    },

    /**
     * Before render widget
     */
    beforeRender: function () {
      // Do something before rendering the widget
    },

    /**
     * After render widget
     */
    afterRender: function () {
      // Do something after rendering the widget
    },

    /**
     * Reset widget
     */
    reset: function () {
      if (this.widgetId) {
        captchafox.reset(this.widgetId);
      }
    },
  });
});
