<template>
  <div
      ref="dialog"
      class="modal modal-task-preview fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="false"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Предпросмотр</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div style="width:320px; height: 240px;" class="mb-3">
            <img :src="picturePreview" style="object-fit: cover; width:100%; height: 100%;" alt="">
          </div>
          <h3>{{ title }}</h3>
          <div class="editor-area mt-2" v-html="content"/>
        </div>
        <div class="modal-footer">
          <button type="button" v-on:click="hide" class="btn btn-primary">Закрыть</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      title: null,
      content: null,
      picturePreview: null,
    };
  },
  methods: {
    show (task) {
      $(this.$refs.dialog).modal('show');
      this.content = task.content;
      this.title = task.title
      if (!this.file && task.picturePreview) {
        this.picturePreview = task.picturePreview;
      }
    },
    hide () {
      $(this.$refs.dialog).modal('hide');
      this.content = null;
      this.file = null;
    }
  }
};
</script>
