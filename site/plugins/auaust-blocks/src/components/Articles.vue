<template>
  <k-block-figure
    :is-empty="!source.url"
    empty-icon="layers"
    :empty-text="empty"
    @open="open"
    @update="update"
  >
    <div>
      {{ content }}
    </div>
  </k-block-figure>
</template>

<script>
export default {
  computed: {
    source() {
      return this.content;
    },
    empty() {
      console.log(this);
    },
  },
};
</script>
