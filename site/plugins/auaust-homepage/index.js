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
          <div
            v-for="option in this.field('size').options"
          >
            {{ option }}
          </div>
          <br />
          <br />
          <br />
          {{ this.field('size') }}
        </div>
      `,
    },
  },
});
