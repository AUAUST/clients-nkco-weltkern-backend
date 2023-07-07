<template>
  <div class="aa-spacer-columns-container">
    <k-toggles-field :options="sizes" :value="size" @update="update">
    </k-toggles-field>
    <div>
      <hr :class="`spacer spacer-${size}`" />
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    sizes() {
      return this.$props.fieldset.tabs.settings.fields.size.options;
    },
    size() {
      return this.content.size;
    },
  },
};
</script>

<style lang="scss">
.k-block-type-spacer {
  hr {
    border: 0;
    border-top: 2px solid var(--color-gray-400);

    &.spacer-xs {
      margin-block: 0;
    }
    &.spacer-s {
      margin-block: 1.5rem;
    }
    &.spacer-m {
      margin-block: 3rem;
    }
    &.spacer-l {
      margin-block: 5rem;
    }
    &.spacer-xl {
      margin-block: 7rem;
    }
  }
  .k-field-header {
    display: none;
  }
  .k-toggles-field {
    height: fit-content;
  }
}
.aa-spacer-columns-container {
  display: grid;
  grid-template-columns: auto 1fr;
  align-items: center;
}
</style>
