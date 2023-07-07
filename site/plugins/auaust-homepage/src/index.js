panel.plugin("auaust/homepage", {
  fields: {
    "homepage-hero": {
      extends: "k-pages-field",
    },
  },
  blocks: {
    spacer: {
      template: `
        <div class="spacer-block">
          {{ console.log(this.field('size') === this) }}
        </div>
      `,
    },
  },
});
