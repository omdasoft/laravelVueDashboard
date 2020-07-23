<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                        <h3 class="widget-user-username">{{form.name}}</h3>
                        <h5 class="widget-user-desc">Admin</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" :src="displayProfileImage()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="activity">
                                user activety
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" v-model="form.name" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">

                                            <has-error :form="form" field="email"></has-error>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" v-model="form.password" class="form-control" id="password" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                                            <has-error :form="form" field="password"></has-error>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image" class="col-sm-2 col-form-label">Choose File</label>
                                        <div class="col-sm-10">
                                            <input type="file" @change="updateProfile" name="image" id="image" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" @click.prevent="updateInfo" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                user: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    image: ''
                })
            }
        },
        methods: {
            displayProfileImage(){
                let image = (this.form.image.length > 200) ? this.form.image : "admin/img/profile/"+this.form.image ;
                return image;
            },
            updateInfo(){
                this.$Progress.start();
                this.form.put("api/profile")
                .then(() => {

                    swal.fire(
                        'Updated!',
                        'the user Profile Updated successfully.',
                        'success'
                    )
                    this.$Progress.finish();
                })
                .catch(()=> {
                    this.$Progress.fail();
                });
            },
            updateProfile(e){
                //console.log("uploading");
                let file = e.target.files[0];
                console.log(file);
                if(file['size'] < 2111775 ){
                    let reader = new FileReader();
                    reader.onloadend = (file) => {
                        //console.log("RESULT", reader.result);
                        this.form.image = reader.result;
                    }
                    reader.readAsDataURL(file);
                }else {
                    swal.fire(

                        'Oops..',
                        'the uploaded file size is large',
                        'error'
                    )
                }
            }
        },
        mounted() {
            axios.get("api/profile")
            .then(({data}) => (this.form.fill(data)));
        }
    }
</script>
