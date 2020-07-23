<template>
    <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fa fa-user-plus fa-fw"></i> </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Registration Date</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users" :key="user.id">

                      <td>{{user.id}}</td>
                      <td>{{user.name | upText}}</td>
                      <td>{{user.email}}</td>
                      <td>{{user.created_at | myDate}}</td>
                      <td>
                        <a href="#" @click="editModal(user)">
                            <i class="fa fa fa-edit orange"></i>
                        </a>
                        |
                         <a href="#" @click="deleteUser(user.id)">
                            <i class="fa fa fa-trash  red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="modal fade" id="addNew" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" v-show="!editmode">Add New User</h4>
                  <h4 class="modal-title" v-show="editmode">Update User</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <form @submit.prevent = "editmode ? updateUser():createUser()" method="post">
                  <div class="modal-body">
                    <div class="form-group">
                      <input v-model="form.name" type="text" name="name" id="name" placeholder="enter name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                      <input v-model="form.email" type="text" name="email" id="email" placeholder="enter email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                      <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                      <input v-model="form.password" type="password" name="password" id="password" placeholder="leave blank to use the old password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                      <has-error :form="form" field="password"></has-error>
                    </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" v-show="editmode" class="btn btn-success"><i class="fa fa-plus"></i> Update</button>
                    <button type="submit" v-show="!editmode" class="btn btn-primary"><i class="fa fa-plus"></i> Create</button>
                  </div>
                </form>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                editmode: false,
                users: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                })
            }
        },
        methods: {
          updateUser(){
            this.$Progress.start();
            this.form.put('api/user/'+this.form.id)
            .then(() => {
              $('#addNew').modal('hide');
               Fire.$emit('AfterUserCreated');
                    swal.fire(
                    'Updated!',
                    'the user Updated successfully.',
                    'success'
                    )
                     this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
               swal("Failed !","there was something wrong","warning");
            })
          },
          newModal(){
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
          },
           editModal(user){
            this.editmode = true;
            $('#addNew').modal('show');
            this.form.fill(user);
          },
          deleteUser(id){

              swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.value) {
                  this.form.delete('api/user/'+id).then(() => {
                     Fire.$emit('AfterUserCreated');
                    swal.fire(
                    'Deleted!',
                    'the user Deleted successfully.',
                    'success'
                    )
                  }).catch(() => {
                    swal("Failed !","there was something wrong","warning");
                  })

                }
              })
          },

          loadUsers(){
            axios.get("api/user").then(({data}) => (this.users = data.data));
          },

          createUser(){

            this.$Progress.start();
            this.form.post('api/user')
            .then(() => {
             Fire.$emit('AfterUserCreated');

            $('#addNew').modal('hide');

            toast.fire({
              icon: 'success',
              title: 'User Created successfully'
            });

            })

            .catch(() => {

            })



            this.$Progress.finish();
          }
        },

        mounted() {
          this.loadUsers();
          //setInterval(() => this.loadUsers(), 3000);
          Fire.$on('AfterUserCreated', () => {
            this.loadUsers();

          });

        }
    }
</script>
