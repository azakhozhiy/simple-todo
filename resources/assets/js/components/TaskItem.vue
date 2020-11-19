<template>
  <div
      :ref="`task-card-${localTask.id}`"
      :data-task-id="localTask.id"
      :class="cardClasses"
  >
    <div class="card-header">
      <h5 class="mb-0">
        <button
            class="btn btn-link"
            data-toggle="collapse"
            :data-target="`#task-${localTask.id}`"
            aria-expanded="true"
            v-on:click="toggleCollapse"
            aria-controls="collapseOne">
          {{ localTask.title }}
        </button>
      </h5>
      <div class="spacer"></div>
      <div class="task-card__actions">
        <template v-if="userIsLogged">
          <button
              type="button"
              ref="buttonSwitcher"
              data-toggle="tooltip"
              data-placement="top"
              :title="localTask.is_complete ? 'Перенести в незавершенные' : 'Завершить'"
              v-on:click="toggleComplete"
              :class="switchClasses"
          >
            <span
                class="material-icons"
                v-html="localTask.is_complete ? 'unpublished' : 'assignment_turned_in'"
            />
          </button>

          <button
              type="button"
              class="btn btn-icon btn-icon--primary"
              data-toggle="tooltip"
              ref="buttonEdit"
              v-on:click="edit"
              data-placement="top"
              title="Редактировать"
          >
            <span class="material-icons">create</span>
          </button>
        </template>
        <template v-else>
          <div
              v-if="localTask.is_complete"
              style="line-height: normal; color: #12b038"
              data-toggle="tooltip"
              ref="successIndicator"
              title="Задача завершена"
              data-placement="top"
          >
            <span class="material-icons">check</span>
          </div>
        </template>
      </div>
    </div>

    <div
        id="<?php echo 'task-'.$task['id'] ?>"
        :class="collapseClasses" aria-labelledby="headingOne"
        data-parent="#accordion"
    >
      <div class="card-body" v-if="localTask.isOpen">

        <div class="task-picture">
          <img :src="localTask.picturePreview" alt="">
        </div>

        <ul class="list-unstyled">
          <li>Дата создания: {{ localTask.created_at }}</li>

          <li>Назначено: {{ localTask.email }}</li>

          <li>Статус: {{ localTask.is_complete ? 'Выполнена' : 'Не выполнена' }}</li>

          <li v-if="localTask.creator_id">
            Создатель: {{ localTask.creator.name }}
          </li>
        </ul>

        <div class="mt-2" v-html="localTask.content"/>
      </div>
    </div>
  </div>

</template>
<script>
import http from '../http';

export default {
  props: {
    value: {
      type: Object,
      default: () => {

      }
    },
    userIsLogged: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    cardClasses () {
      return {
        'card': true,
        'task-card': true,
        'task-card--completed': this.localTask.is_complete,
        'mb-2': true,
      };
    },
    collapseClasses () {
      return {
        'collapse': true,
        'show': this.localTask.isOpen
      };
    },
    switchClasses () {
      return {
        'btn': true,
        'btn-icon': true,
        'btn-icon--success': !this.localTask.is_complete,
        'btn-icon--danger': this.localTask.is_complete,
        'mr-2': true
      };
    }
  },
  watch: {
    value: function (v) {
      this.localTask = v;
    },
    localTask: function (v) {
      this.$emit('input', v);
    }
  },
  data () {
    return {
      localTask: this.value,
    };
  },
  created () {
    if (this.userIsLogged) {
      $(this.$refs.buttonSwitcher).tooltip();
      $(this.$refs.buttonEdit).tooltip();
    } else {
      $(this.$refs.successIndicator).tooltip();
    }
  },
  methods: {
    edit () {
      this.reloadTask()
          .then(res => {
            this.$emit('edit', this.localTask);
          });
    },
    toggleComplete () {
      http.post(`?module=tasks&action=toggle&task_id=${ this.localTask.id }`)
          .then(res => {
            const completed = res.data.is_completed;
            this.localTask.is_complete = completed;

            $.notifier.callSystem({
              type: completed ? 'done' : 'info',
              icon: 'check',
              text: completed ? 'Задача завершена!' : 'Задача перенесена в незавершенные!'
            });

            const tooltip = completed ? 'Перенести в незавершенные' : 'Завершить';

            this.$refs.buttonSwitcher.setAttribute('data-original-title', tooltip);
          });
    },
    toggleCollapse () {
      this.reloadTask()
          .then(res => {
            this.localTask.isOpen = !this.localTask.isOpen;
            if (this.localTask.isOpen) {
              this.$emit('closeOther', this.localTask);
            }
          });
    },
    reloadTask () {
      return new Promise((resolve, reject) => {
        http.get(`?module=tasks&action=getOne&task_id=${ this.localTask.id }`)
            .then(res => {
              resolve(res);
              this.localTask = {
                ...this.localTask,
                ...res.data
              };
            })
            .catch(e => {
              reject(e);
            });
      });
    }
  },
};
</script>
