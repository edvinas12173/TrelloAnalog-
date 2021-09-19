<template>
    <div class="container">
        <p class="mt-3 mb-3 display-6">
            Desks
        </p>
        <hr>
        <div class="card">
            <div class="card-body bg-light">
                <form @submit.prevent="addNewDesk">
                    <div class="input-group">
                        <input type="text" v-model="name" class="form-control" :class="{ 'is-invalid': $v.name.$error }" placeholder="Enter Desk name">
                        <button type="submit" class="btn btn-success">Add new desk</button>
                        <div class="invalid-feedback" v-if="!$v.name.required">
                            Enter the <b>Desk</b> name!
                        </div>
                        <div class="invalid-feedback" v-if="!$v.name.maxLength">
                            Max of <b>{{$v.name.$params.maxLength.max}}</b> symbols!
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="alert alert-danger" role="alert" v-if="errored">
            Error for load data! <br>
        </div>
        <div class="row">
            <div class="col-lg-4" v-for="desk in desks">
                <div class="card mt-3">
                    <div class="card-header" style="display: inline-block">
                        <router-link style="display: inline-block;" :to="{name: 'showDesk', params: {deskId: desk.id}}">
                            <h6 class="card-title">{{ desk.name }}</h6>
                        </router-link>
                        <span @click="deleteDesk(desk.id, desk.name)" style="float: right; display: inline-block; cursor: pointer"><i class="far fa-trash-alt"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center" v-if="loading">
            <strong>Loading...</strong>
            <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
        </div>
    </div>
</template>

<script>
    import { required, maxLength } from 'vuelidate/lib/validators'
    export default {
        data() {
            return {
                desks: [],
                errored: false,
                errors: [],
                loading: true,
                name: null,
            }
        },
        mounted() {
            this.getAllDesks()
        },
        methods: {
            getAllDesks() {
                axios.get('/api/v1/desks')
                    .then(response => {
                        this.desks = response.data.data
                    })
                    .catch(error => {
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },
            addNewDesk() {
                this.$v.$touch()
                if(this.$v.$anyError){
                    return;
                }
                axios.post('/api/v1/desks', {
                    name: this.name,
                })
                    .then(response => {
                        this.$v.$reset()
                        this.name = ''
                        this.desks = []
                        this.getAllDesks()
                    })
                    .catch(error => {
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },
            deleteDesk(id, name) {
                if(confirm('Are you sure delete '+name+'?')) {
                    axios.post('/api/v1/desks/'+id, {
                        _method: 'DELETE'
                    })
                        .then(response => {
                            this.desks = []
                            this.getAllDesks()
                        })
                        .catch(error => {
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
            }
        }
    }
</script>

