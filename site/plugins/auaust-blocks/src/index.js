import Spacer from "./components/Spacer.vue";
import Articles from "./components/Articles.vue";

panel.plugin("auaust/homepage", {
  fields: {
    "homepage-hero": {
      extends: "k-pages-field",
    },
  },
  blocks: {
    spacer: Spacer,
    articles: Articles,
  },
});
