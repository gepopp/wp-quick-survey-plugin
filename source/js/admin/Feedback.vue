<template>
  <div class="mb-4">
    <label class="mb-5 font-semibold"><?php _e( 'Feedback aktivieren', 'quick-survey' ) ?></label>
    <div class="flex space-x-5 mb-4">
      <label>
        <input type="radio" name="qsy[feedback]" value="y" <?php checked( $value['feedback'] ?? 'y', 'y', true ) ?>> <?php _e( 'JA', 'quick-survey' ) ?>
      </label>
      <label>
        <input type="radio" name="qsy[feedback]" value="n" <?php checked( $value['feedback'] ?? 'n', 'n', true ) ?>> <?php _e( 'NEIN', 'quick-survey' ) ?>
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: "Feedback"
}
</script>

<style scoped>

</style>