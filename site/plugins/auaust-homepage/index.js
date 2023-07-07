(function() {
  "use strict";
  const Spacer_vue_vue_type_style_index_0_lang = "";
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    if (functionalTemplate) {
      options.functional = true;
    }
    if (scopeId) {
      options._scopeId = "data-v-" + scopeId;
    }
    var hook;
    if (moduleIdentifier) {
      hook = function(context) {
        context = context || // cached call
        this.$vnode && this.$vnode.ssrContext || // stateful
        this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext;
        if (!context && typeof __VUE_SSR_CONTEXT__ !== "undefined") {
          context = __VUE_SSR_CONTEXT__;
        }
        if (injectStyles) {
          injectStyles.call(this, context);
        }
        if (context && context._registeredComponents) {
          context._registeredComponents.add(moduleIdentifier);
        }
      };
      options._ssrRegister = hook;
    } else if (injectStyles) {
      hook = shadowMode ? function() {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        );
      } : injectStyles;
    }
    if (hook) {
      if (options.functional) {
        options._injectStyles = hook;
        var originalRender = options.render;
        options.render = function renderWithStyleInjection(h, context) {
          hook.call(context);
          return originalRender(h, context);
        };
      } else {
        var existing = options.beforeCreate;
        options.beforeCreate = existing ? [].concat(existing, hook) : [hook];
      }
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const _sfc_main$1 = {
    computed: {
      sizes() {
        return this.$props.fieldset.tabs.settings.fields.size.options;
      },
      size() {
        return this.content.size;
      }
    }
  };
  var _sfc_render$1 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { staticClass: "aa-spacer-columns-container" }, [_c("k-toggles-field", { attrs: { "options": _vm.sizes, "value": _vm.size }, on: { "input": function($event) {
      return _vm.update({ size: $event });
    } } }), _c("div", [_c("hr", { class: `spacer spacer-${_vm.size}` })])], 1);
  };
  var _sfc_staticRenderFns$1 = [];
  _sfc_render$1._withStripped = true;
  var __component__$1 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$1,
    _sfc_render$1,
    _sfc_staticRenderFns$1,
    false,
    null,
    null,
    null,
    null
  );
  __component__$1.options.__file = "/Users/aurianaubert/Desktop/NK+CO | WELTKERN/003 WK®2.0/DÉVELOPPEMENT/BACKEND/site/plugins/auaust-homepage/src/components/Spacer.vue";
  const Spacer = __component__$1.exports;
  const _sfc_main = {
    computed: {
      source() {
        return this.content;
      },
      empty() {
        console.log(this);
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-block-figure", { attrs: { "is-empty": !_vm.source.url, "empty-icon": "layers", "empty-text": _vm.empty }, on: { "open": _vm.open, "update": _vm.update } }, [_c("div", [_vm._v(" " + _vm._s(_vm.content) + " ")])]);
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns,
    false,
    null,
    null,
    null,
    null
  );
  __component__.options.__file = "/Users/aurianaubert/Desktop/NK+CO | WELTKERN/003 WK®2.0/DÉVELOPPEMENT/BACKEND/site/plugins/auaust-homepage/src/components/Articles.vue";
  const Articles = __component__.exports;
  panel.plugin("auaust/homepage", {
    fields: {
      "homepage-hero": {
        extends: "k-pages-field"
      }
    },
    blocks: {
      spacer: Spacer,
      articles: Articles
    }
  });
})();
