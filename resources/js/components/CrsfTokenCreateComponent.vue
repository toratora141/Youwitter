<template>
  <form>
    <input type="hidden" name="_token" v-bind:value="csrf">
  </form>
</template>

<script>
  export default {
    props:  {
      csrf: {
        type: String,
        required: true,
      }
    }
  }
</script>
