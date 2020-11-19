<template>
  <div class="task-page__form">
    <div class="task-page__form__header">
      <h4 style="margin-bottom:0;">{{ formTitle }}</h4>
      <div class="spacer"></div>
      <button
          class="btn btn-danger"
          v-if="localTask.id"
          type="button"
          v-on:click="editCancel"
      >
        Отменить
      </button>
    </div>

    <div class="card mt-4">
      <div class="card-body">
        <form>
          <div class="form-group" style="overflow: hidden">
            <label for="formTaskTitle">Название</label>
            <input
                class="form-control"
                :value="localTask.title"
                v-on:input="inputField('title', $event.target.value)"
                id="formTaskTitle"
                placeholder="Например: доработать модуль авторизации"
            >
          </div>

          <div class="form-group" style="overflow: hidden">
            <label for="taskEmail">Ответственный за задачу</label>
            <input
                class="form-control"
                type="email"
                :value="localTask.email"
                v-on:input="inputField('email', $event.target.value)"
                id="taskEmail"
                placeholder="Введите почту"
            >
          </div>

          <div class="form-group">
            <label for="formTaskPicture">Обложка</label>
            <div class="row">
              <div class="col-md-4">
                <img :src="localTask.picturePreview" style="width:100%;"/>
              </div>
              <div class="col-md-8">
                <input
                    style="overflow: hidden"
                    type="file"
                    v-on:change="changePicture"
                    ref="picture"
                    v-if="showUploader"
                    accept="image/jpeg, image/gif, image/png"
                    class="form-control"
                    id="formTaskPicture"
                >

                <div class="mt-2" style="font-size:12px; ">
                  <p style="margin-bottom: 0;">Поддерживаемые расширения: png, jpg, jpeg, gif</p>
                  <p style="margin-top:0;">Рекомендуемый размер изображения: 320x240</p>
                </div>

                <div class="mt-2">
                  <button
                      v-if="localTask.picture"
                      v-on:click="clearUpload"
                      class="btn btn-primary"
                  >
                    Очистить
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="taskEditor">Описание задачи</label>
            <div ref="editor" id="taskEditor"></div>
            <button
                type="button"
                class="btn btn-primary mt-2"
                v-on:click="showPreview"
                id="buttonTaskPreview"
            >
              Предпросмотр
            </button>
          </div>
          <hr>
          <div class="actions">
            <button
                type="button"
                v-on:click="touch"
                id="taskSaveButton"
                class="btn btn-success"
                :disabled="!localTask.title || !localTask.email"
            >
              Сохранить
            </button>
          </div>
        </form>
      </div>
    </div>

    <task-preview ref="taskPreview"></task-preview>
  </div>
</template>
<script>
import TaskPreview from './TaskPreview';
import http from '../http';

export default {
  components: {TaskPreview},
  props: {
    value: {
      type: Object,
      default: () => {

      }
    }
  },
  computed: {
    formTitle () {
      return this.localTask.id ? `Редактирование задачи #${ this.localTask.id }` : 'Добавление задачи';
    }
  },
  watch: {
    value: function (v) {
      this.localTask = v;
      this.resetUploader();
      this.setContentFromTask();
    },
    localTask: function (v) {
      this.$emit('input', v);
    }
  },
  data () {
    return {
      localTask: this.value,
      tempPreview: null,
      showUploader: true,
    };
  },
  mounted () {
    $(this.$refs.editor).summernote({
      placeholder: 'Опишите подробно суть задачи',
      tabsize: 2,
      height: 250,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['codeview', 'help']]
      ],
      callbacks: {
        onChange: (e) => {
          this.inputField('content', e);
        }
      }
    });

    this.tempPreview = this.localTask.picturePreview;
  },
  methods: {
    setContentFromTask () {
      $(this.$refs.editor).summernote('code', this.localTask.content);
    },
    inputField (field, value) {
      this.localTask[field] = value;
    },
    editCancel () {
      this.resetUploader();
      this.$emit('editCancel');
    },
    showPreview () {
      this.$refs.taskPreview.show(this.localTask);
    },
    changePicture () {
      const file = this.$refs.picture.files[0];
      let reader = new FileReader();
      this.tempPreview = this.localTask.picturePreview;
      reader.addEventListener('load', function () {
        let image = new Image();
        image.src = reader.result;

        image.addEventListener('load', function () {
          this.localTask.picturePreview = reader.result;
          this.localTask.picture = file;
        }.bind(this), false);
      }.bind(this), false);

      if (file) {
        reader.readAsDataURL(file);
      }
    },
    resetUploader () {
      this.showUploader = false;
      this.$nextTick(() => {
        this.showUploader = true;
      });
    },
    clearUpload () {
      this.localTask.picture = null;
      this.localTask.picturePreview = this.tempPreview;
      this.resetUploader();
    },
    touch () {
      if (this.localTask.title) {
        const formData = new FormData();
        formData.append('content', this.localTask.content);
        formData.append('id', this.localTask.id);
        formData.append('title', this.localTask.title);
        formData.append('email', this.localTask.email)

        if (this.localTask.picture) {
          formData.append('picture', this.localTask.picture);
        }

        http.post('?module=tasks&action=touch', formData)
            .then(res => {
              $.notifier.callSystem({
                type: 'done',
                icon: 'check',
                text: 'Задача успешно добавлена!'
              });
              this.$emit('reload')

              this.editCancel()
            })
            .catch(e => {
              $.notifier.callSystem({
                type: 'error',
                icon: 'close',
                text: 'Произошла ошибка!'
              });
            })
            .finally(() => {

            });
      }
    }
  }
};
</script>
