<template>
  <div class="container">
      <form class="mb-3" @submit.prevent="saveName" v-if="desk_list_name_input">
          <div class="input-group mt-3">
              <input type="text" v-model="name" class="form-control" :class="{ 'is-invalid': $v.name.$error }">
              <button type="submit" class="btn" @click="saveName"><i class="fas fa-times"></i></button>
              <div class="invalid-feedback" v-if="!$v.name.required">
                  Enter the <b>Desk</b> name!
              </div>
              <div class="invalid-feedback" v-if="!$v.name.maxLength">
                  Max of <b>{{$v.name.$params.maxLength.max}}</b> symbols!
              </div>
          </div>
      </form>
      <h6 class="mt-3 mb-3" v-if="!desk_list_name_input">
          {{name}} <i @click="desk_list_name_input = true" class="fas fa-pencil-alt ml-3" style="cursor: pointer"></i>
      </h6>
      <div class="card">
          <div class="card-body bg-light">
              <form @submit.prevent="addNewDeskList">
                  <div class="input-group">
                      <input type="text" v-model="desk_list_name" class="form-control" :class="{ 'is-invalid': $v.desk_list_name.$error }" placeholder="Enter Desk List name">
                      <button type="submit" class="btn btn-success">Add new desk list</button>
                      <div class="invalid-feedback" v-if="!$v.desk_list_name.required">
                          Enter the <b>Desk List</b> name!
                      </div>
                      <div class="invalid-feedback" v-if="!$v.desk_list_name.maxLength">
                          Max of <b>{{$v.desk_list_name.$params.maxLength.max}}</b> symbols!
                      </div>
                  </div>
              </form>
          </div>
      </div>
      <div class="alert alert-danger" role="alert" v-if="errored">
          Error for load data!
      </div>
      <div class="row">
          <div class="col-lg-4" v-for="(desk_list, index) in desk_lists">
              <div class="card mt-3">
                  <div class="card-header" v-if="desk_list_input_id == desk_list.id">
                      <form @submit.prevent="updateDeskList(desk_list.id, desk_list.name)" class="d-flex">
                          <div class="input-group">
                              <input type="text" v-model="desk_list.name" class="form-control" :class="{ 'is-invalid': $v.desk_lists.$each[index].name.$error }">
                              <button type="submit" class="btn" @click="updateDeskList(desk_list.id, desk_list.name)"><i class="fas fa-times"></i></button>
                              <div class="invalid-feedback" v-if="!$v.desk_lists.$each[index].name.required">
                                  Enter the <b>Desk List</b> name!
                              </div>
                              <div class="invalid-feedback" v-if="!$v.desk_lists.$each[index].name.maxLength">
                                  Max of <b>{{$v.desk_lists.$each[index].name.$params.maxLength.max}}</b> symbols!
                              </div>
                          </div>
                      </form>
                  </div>
                  <div class="card-header" v-else style="display: inline-block">
                      {{desk_list.name}}
                      <span @click="deleteDeskList(desk_list.id, desk_list.name)" class="ml-1" style="float: right; cursor: pointer"><i class="far fa-trash-alt"></i></span>
                      <span @click="desk_list_input_id = desk_list.id" style="float: right; cursor: pointer"><i class="fas fa-pencil-alt"></i></span>
                  </div>
                  <a class="card-body">
                      <form @submit.prevent="addNewCard(desk_list.id)">
                          <input type="text" v-model="card_names[desk_list.id]" class="form-control" placeholder="Enter card name" :class="{ 'is-invalid': $v.card_names.$each[desk_list.id].$error }">
                          <div class="invalid-feedback" v-if="!$v.card_names.$each[desk_list.id].required">
                              Enter the <b>Card</b> name!
                          </div>
                          <div class="invalid-feedback" v-if="!$v.card_names.$each[desk_list.id].maxLength">
                              Max of <b>{{$v.card_names.$each[desk_list.id].$params.maxLength.max}}</b> symbols!
                          </div>
                      </form>
                      <div class="card mt-3 bg-light" v-for="card in desk_list.cards" :key="card.id">
                          <div class="card-header" style="display: inline-block">
                              {{ card.name }}
                              <span @click="deleteCard(card.id, card.name)" style="float: right; cursor: pointer"><i class="far fa-trash-alt"></i></span>
                          </div>
                          <div class="card-footer">
                              <div class="row">
                                  <button type="button" @click="getCard(card.id)" class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                      Open tasks
                                  </button>
                              </div>
                          </div>
                      </div>
                      <!-- Modal -->
                      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-centered">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <form @submit.prevent="updateCardName" v-if="show_card_name_input">
                                          <div class="input-group">
                                              <input type="text" v-model="current_card.name" class="form-control" :class="{ 'is-invalid': $v.current_card.name.$error }">
                                              <button type="submit" class="btn" @click="updateCardName"><i class="fas fa-times"></i></button>
                                              <div class="invalid-feedback" v-if="!$v.current_card.name.required">
                                                  Enter the <b>Card</b> name!
                                              </div>
                                              <div class="invalid-feedback" v-if="!$v.current_card.name.maxLength">
                                                  Max of <b>{{$v.current_card.name.$params.maxLength.max}}</b> symbols!
                                              </div>
                                          </div>
                                      </form>
                                      <div class="modal-title" v-if="!show_card_name_input">
                                          <h4>{{current_card.name}} <i @click="show_card_name_input = true" class="fas fa-pencil-alt ml-3"></i></h4>
                                      </div>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                      <div class="mt-1" v-for="(task, index) in current_card.tasks">
                                          <div v-if="task_input_name_id == task.id" >
                                              <form @submit.prevent="updateTask(current_card.tasks[index])">
                                                  <div class="input-group">
                                                      <input type="text" v-model="current_card.tasks[index].name" class="form-control" placeholder="Enter Task name">
                                                      <button type="submit" class="btn" @click="updateTask(current_card.tasks[index])"><i class="fas fa-times"></i></button>
                                                  </div>
                                              </form>
                                          </div>
                                          <div v-else>
                                              <input class="form-check-input" type="checkbox" @change="updateTask(current_card.tasks[index])" :id="'inlineCheckbox'+index" v-model="current_card.tasks[index].is_done">
                                              <label class="form-check-label" :class="{ 'text-success text-decoration-line-through': task.is_done == true}" :for="'inlineCheckbox'+index">{{task.name}}</label>
                                              <span>
                                                  <i @click="deleteTask(task.id, task.name)" style="float: right; cursor: pointer" class="far fa-trash-alt"></i>
                                                  <i @click="task_input_name_id = task.id" style="float: right; cursor: pointer" class="fas fa-pencil-alt ml-3"></i>
                                              </span>
                                          </div>
                                          <hr>
                                      </div>
                                      <form @submit.prevent="addNewTask" class="mt-3">
                                          <div class="input-group">
                                              <input type="text" v-model="new_task_name" class="form-control" :class="{ 'is-invalid': $v.new_task_name.$error }" placeholder="Enter Task name">
                                              <button type="submit" class="btn btn-success">Add new task</button>
                                              <div class="invalid-feedback" v-if="!$v.new_task_name.required">
                                                  Enter the <b>Task</b> name!
                                              </div>
                                              <div class="invalid-feedback" v-if="!$v.new_task_name.maxLength">
                                                  Max of <b>{{$v.new_task_name.$params.maxLength.max}}</b> symbols!
                                              </div>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- End Modal -->
                  </a>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
    import { required, maxLength } from 'vuelidate/lib/validators'
    export default {
        props: [
            'deskId'
        ],
        data() {
            return {
                name: null,
                errored: false,
                loading: true,
                desk_lists: true,
                desk_list_name: null,
                desk_list_name_input: false,
                desk_list_input_id: null,
                card_names: [],
                current_card: [],
                show_card_name_input: false,
                new_task_name: '',
                task_input_name_id: null,
            }
        },
        mounted() {
            axios.get('/api/v1/desks/'+this.deskId)
                .then(response => {
                    this.name = response.data.data.name
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
            this.getDeskLists()
        },
        methods: {
            saveName() {
                this.$v.name.$touch()
                if(this.$v.name.$anyError){
                    return;
                }
                axios.post('/api/v1/desks/'+this.deskId, {
                    _method: 'PUT',
                    name: this.name,
                })
                    .then(response => {
                        this.desk_list_name_input = false
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.errored = true
                    })
            },
            getDeskLists() {
                axios.get('/api/v1/desk-lists', {
                    params: {
                        desk_id: this.deskId
                    }
                })
                    .then(response => {
                        this.desk_lists = response.data.data
                        this.card_names = []
                        this.desk_lists.forEach(el => {
                            this.card_names[el.id] = ''
                        })
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
            },
            getCard(id) {
                axios.get('/api/v1/cards/' + id)
                    .then(response => {
                        this.current_card = response.data.data
                        console.log(this.current_card)
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
            },
            addNewDeskList() {
                this.$v.desk_list_name.$touch()
                if(this.$v.desk_list_name.$anyError){
                    return;
                }
                axios.post('/api/v1/desk-lists', {
                    name: this.desk_list_name,
                    desk_id: this.deskId,
                })
                    .then(response => {
                        this.$v.$reset()
                        this.desk_list_name = ''
                        this.desk_lists = []
                        this.getDeskLists()
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },
            addNewCard(desk_list_id) {
                this.$v.card_names.$each[desk_list_id].$touch()
                if(this.$v.card_names.$each[desk_list_id].$anyError){
                    return;
                }
                axios.post('/api/v1/cards', {
                    name: this.card_names[desk_list_id],
                    desk_list_id
                })
                    .then(response => {
                        this.$v.$reset()
                        this.getDeskLists()
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            addNewTask() {
                this.$v.new_task_name.$touch()
                if(this.$v.new_task_name.$anyError){
                    return;
                }
                axios.post('/api/v1/tasks', {
                    name: this.new_task_name,
                    card_id: this.current_card.id
                })
                    .then(response => {
                        this.new_task_name = ''
                        this.$v.$reset()
                        this.getCard(this.current_card.id)
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
            },
            updateDeskList(id, name) {
                this.$v.desk_lists.$touch()
                if(this.$v.desk_lists.$anyError){
                    return;
                }
                this.desk_list_input_id = null
                axios.post('/api/v1/desk-lists/'+id, {
                    _method: 'PUT',
                    name
                })
                    .then(response => {
                        this.desk_list_input_id = null
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
            },
            updateCardName() {
                this.$v.current_card.name.$touch()
                if(this.$v.current_card.name.$anyError){
                    return;
                }
                this.show_card_name_input = false
                axios.post('/api/v1/cards/' + this.current_card.id, {
                    _method: 'PUT',
                    name: this.current_card.name,
                    desk_list_id: this.current_card.desk_list_id
                })
                    .then(response => {
                        this.show_card_name_input = false
                        this.$v.$reset()
                        this.getDeskLists()
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
            },
            updateTask(task) {
                axios.post('/api/v1/tasks/'+task.id, {
                    _method: 'PUT',
                    name: task.name,
                    is_done: task.is_done,
                    card_id: task.card_id
                })
                    .then(response => {
                        this.task_input_name_id = null
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },
            deleteDeskList(id, name) {
                if(confirm('Are you sure delete '+name+'?')) {
                    axios.post('/api/v1/desk-lists/'+id, {
                        _method: 'DELETE'
                    })
                        .then(response => {
                            this.desk_lists = []
                            this.getDeskLists()
                        })
                        .catch(error => {
                            console.log(error)
                            this.errored = true
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }
            },
            deleteCard(id, name) {
                if(confirm('Are you sure delete '+name+'?')) {
                    axios.post('/api/v1/cards/'+id, {
                        _method: 'DELETE'
                    })
                        .then(response => {
                            this.getDeskLists()
                        })
                        .catch(error => {
                            console.log(error)
                            this.errored = true
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }
            },
            deleteTask(id, name) {
                if(confirm('Are you sure delete '+name+'?')) {
                    axios.post('/api/v1/tasks/'+id, {
                        _method: 'DELETE'
                    })
                        .then(response => {
                            this.getCard(this.current_card.id)
                        })
                        .catch(error => {
                            console.log(error)
                            this.errored = true
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }
            }
        },
        validations: {
            name: {
                required,
                maxLength: maxLength(255)
            },
            desk_list_name: {
                required,
                maxLength: maxLength(255)
            },
            card_names: {
                $each: {
                    required,
                    maxLength: maxLength(255)
                }
            },
            current_card: {
                name: {
                    required,
                    maxLength: maxLength(255)
                }
            },
            desk_lists: {
                $each: {
                    name: {
                        required,
                        maxLength: maxLength(255)
                    }
                }
            },
            new_task_name: {
                required,
                maxLength: maxLength(255)
            },
        }
    }
</script>
