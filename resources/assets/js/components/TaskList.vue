<template>
  <div class="task-page__list">
    <h4>Список задач</h4>

    <div class="task-page__list__filters mt-4" v-if="$slots.filters">
      <slot name="filters"></slot>
    </div>

    <div id="accordion" v-if="tasks.length > 0" class="tasks-list mt-4">
      <task-item
          :key="index"
          :user-is-logged="userIsLogged"
          v-model="tasks[index]"
          @edit="edit"
          @closeOther="closeOther"
          v-for="(task, index) in tasks"
      />
    </div>
    <div v-else-if="loading && !tasks.length">
      Загрузка задач...
    </div>
    <div v-else>
      Нет предстоящих задач.
    </div>
  </div>
</template>
<script>
import TaskItem from './TaskItem';
import forEach from 'lodash/forEach';

export default {
  components: {TaskItem},
  props: {
    userIsLogged: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    },
    tasks: {
      type: Array,
      default: () => {

      }
    }
  },
  computed: {},
  mounted () {

  },
  watch: {
    tasks: function (v) {
      this.localTasks = v;
    },
    localTasks: function (v) {
      this.$emit('input', v);
    }
  },
  data () {
    return {
      localTasks: this.tasks
    };
  },
  methods: {
    edit (task) {
      this.$emit('edit', task);
    },
    closeOther (task) {
      forEach(this.localTasks, (t, i) => {
        if (+task.id !== +t.id) {
          this.localTasks[i].isOpen = false;
        }
      });
    }
  }
};
</script>
