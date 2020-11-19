<template>
  <div class="task-page">
    <div class="row">
      <div class="col-md-7">
        <task-list
            :tasks="paginationData.data"
            :user-is-logged="userIsLogged"
            @edit="edit"
            :loading="loading"
        >
          <template slot="filters">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="orderBy">Сортировка</label>
                  <select class="form-control" v-model="filters.order_by" id="orderBy">
                    <option value="created_at_desc">Сначала новые</option>
                    <option value="created_at_asc">Сначала старые</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="isComplete">Выполнено</label>
                  <select class="form-control" v-model="filters.status" id="isComplete">
                    <option value="completed">Выполнено</option>
                    <option value="not_completed">Невыполнено</option>
                    <option value="all">Показать все</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="search">Поиск</label>
                  <input
                      class="form-control"
                      id="search"
                      placeholder="Введите название задачи или email пользователя"
                      v-model="filters.search"
                  >
                </div>
              </div>
            </div>

            <button class="btn btn-primary" v-on:click="getTasks" :disabled="loading">
              Загрузить
            </button>

            <button class="btn btn-link ml-3" v-on:click="clearFilters" :disabled="loading">
              Очистить фильтры
            </button>
          </template>
        </task-list>

        <nav aria-label="Page navigation example" v-if="paginationData.last_page > 1">
          <ul class="pagination">
            <li :class="{'page-item': true, 'disabled': currentPage === 1}">
              <button class="page-link" type="button" v-on:click="currentPage--" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
              </button>
            </li>
            <li :class="{'page-item': true, 'active': currentPage === i}" v-for="i in paginationData.last_page">
              <button class="page-link" v-on:click="currentPage = i" type="button">{{ i }}</button>
            </li>
            <li :class="{'page-item': true, 'disabled': currentPage === paginationData.last_page}">
              <button class="page-link" type="button" v-on:click="currentPage++" aria-label="Previous">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </button>
            </li>
          </ul>
        </nav>
      </div>
      <div class="col-md-5">
        <task-form
            :user="user"
            v-model="task"
            @reload="reloadItems"
            @editCancel="editCancel"
        />
      </div>
    </div>
  </div>
</template>
<script>
import TaskList from '../components/TaskList';
import TaskForm from '../components/TaskForm.vue';
import http from '../http';
import forEach from 'lodash/forEach';
import cloneDeep from 'lodash/cloneDeep';
import { getQueryObject, updateQueryParam } from '../helpers/url';

export default {
  components: {TaskList, TaskForm},
  computed: {
    userIsLogged () {
      return this.user.hasOwnProperty('id');
    }
  },
  data () {
    return {
      paginationData: {
        data: [],

      },
      searchParams: null,
      currentPage: 1,
      loading: false,
      user: {},
      task: this.emptyTask(),
      filters: this.getDefaultFilters(),
    };
  },
  watch: {
    currentPage: function (v) {
      this.getTasks();
      this.setSearchParam('page', v)
    },
    'filters.search': function(v){
      this.setSearchParam('search', v)
    },
    'filters.status': function(v){
      this.setSearchParam('status', v)
    },
    'filters.order_by': function(v){
      this.setSearchParam('order_by', v)
    }
  },
  created () {
    this.$set(this, 'user', window.user);

    const query = getQueryObject();

    this.searchParams = new URLSearchParams(window.location.search);

    if (query) {
      if (query.order_by) {
        this.filters.order_by = query.order_by;
      }

      if (query.status) {
        this.filters.status = query.status;
      }

      if (query.search) {
        this.filters.search = query.search;
      }

      if (query.page) {
        this.currentPage = +query.page;
      }
    }

    this.getTasks();
  },
  methods: {
    setSearchParam(key, value){
      this.searchParams.set(key, value);
      let newRelativePathQuery = window.location.pathname + '?' + this.searchParams.toString();
      history.pushState(null, '', newRelativePathQuery);
    },
    getDefaultFilters () {
      return {
        'order_by': 'created_at_desc',
        status: 'all',
        search: null,
      };
    },
    emptyTask () {
      return {
        id: null,
        title: null,
        content: null,
        picture: null,
        clearPicture: false,
        pictureUrl: '/?module=files&action=placeholder',
        creator_id: null,
        email: null,
        creator: {
          name: null,
        },
        picture_id: null,
        is_complete: false,
        picturePreview: '/?module=files&action=placeholder'
      };
    },
    getTasks () {
      this.loading = true;
      const order = `&order_by=${ this.filters.order_by }`;
      const status = `&status=${ this.filters.status }`;
      const search = `&search=${ this.filters.search }`;

      let url = `/?module=tasks&action=get`;

      if (this.filters.order_by) {
        url = `${ url }${ order }`;
      }

      if (this.filters.status) {
        url = `${ url }${ status }`;
      }

      if (this.filters.search) {
        url = `${ url }${ search }`;
      }

      if (this.currentPage) {
        url = `${ url }&page=${ this.currentPage }`;
      }

      http.get(url)
          .then(res => {
            this.loading = false;

            const tasks = [];
            forEach(res.data.data, i => {
              tasks.push({
                ...i,
                isOpen: false,
                pictureUrl: i.picture_url,
                picturePreview: i.picture_url
              });
            });

            const result = {
              ...res.data,
              data: tasks,
            };

            this.$set(this, 'paginationData', result);
          });
    },
    edit (task) {
      this.$set(this, 'task', cloneDeep(task));
    },
    editCancel () {
      this.$set(this, 'task', this.emptyTask());
    },
    clearFilters () {
      this.$set(this, 'filters', this.getDefaultFilters());
      this.getTasks();
    },
    reloadItems () {
      if (this.paginationData.current_page === 1) {
        this.getTasks();
      }
    }
  }
};
</script>
