import Spacer from "./components/Spacer.vue";

panel.plugin("auaust/homepage", {
  fields: {
    "homepage-hero": {
      extends: "k-pages-field",
    },
  },
  blocks: {
    spacer: Spacer,
  },
});
