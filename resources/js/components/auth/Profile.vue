<template>
    <div class="container">
        <form autocomplete="off" @submit.prevent="update" enctype="multipart/form-data">
            <div class="card">
                <div class="row no-gutters">
                    <div class="col-md-4 custom-min-h">
                        <img
                            class="card-img h-100"
                            :class="!edit ? 'custom-hover' : ''"
                            :src="sauce"
                            @click="edydAvatar"
                        />
                        <input
                            type="file"
                            name
                            id="prof-avatar"
                            accept="image/x-png, image/gif, image/jpeg"
                            class="d-none"
                            @change="changeAvatar"
                        />
                    </div>
                    <div class="col-md-8">
                        <div class="container my-4">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            v-model="prof.name"
                                            :disabled="edit"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input
                                            class="form-control"
                                            :class="$auth.user().email_verified_at ? 'is-valid' : 'is-warning'"
                                            type="text"
                                            v-model="$auth.user().email"
                                            disabled
                                        />
                                        <small
                                            :class="$auth.user().email_verified_at ? 'text-success' : 'custom-text-warn'"
                                        >{{ $auth.user().email_verified_at ? 'Terverifikasi' : 'Belum Diverifikasi' }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Password Baru</label>
                                        <input
                                            type="password"
                                            v-model="prof.password"
                                            class="form-control"
                                            :class="err[0] ? 'is-invalid' : ''"
                                            :disabled="edit"
                                        />
                                        <small
                                            v-if="prof.password != '' && prof.password !== prof.password_confirmation || err[1]"
                                            class="text-danger"
                                        >The password confirmation does not match.</small>
                                        <br
                                            v-if="prof.password != '' && prof.password.length < 8 || err[0]"
                                        />
                                        <small
                                            v-if="prof.password != '' && prof.password.length < 8 || err[0]"
                                            class="text-danger"
                                        >The password must be at least 8 characters.</small>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Konfirmasi Password Baru</label>
                                        <input
                                            type="password"
                                            v-model="prof.password_confirmation"
                                            class="form-control"
                                            :class="err[1] ? 'is-invalid' : ''"
                                            :disabled="edit"
                                            :required="prof.password !== ''"
                                        />
                                    </div>
                                </div>
                            </div>
                            <button
                                class="btn btn-secondary"
                                @click.prevent="edyd"
                                :disabled="clicked"
                            >
                                <fa icon="pen" />
                                {{ edit ? ' Edit' : ' Batalkan Edit' }}
                            </button>
                            <button v-if="!edit" class="ml-2 btn btn-primary" :disabled="clicked">
                                <fa icon="paper-plane" />
                                {{ ' Update' }}
                            </button>
                            <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data: () => ({
        edit: true,
        sauce: "",
        prof: {
            avatar: "",
            name: "",
            password: "",
            password_confirmation: ""
        },
        clicked: false,
        err: [false, false]
    }),
    created() {
        this.sauce = this.$auth.user().avatar;
        this.prof.name = this.$auth.user().name;
    },
    methods: {
        edyd() {
            if (!this.edit) {
                this.prof = {
                    avatar: "",
                    name: this.$auth.user().name,
                    password: "",
                    password_confirmation: ""
                };
                this.sauce = this.$auth.user().avatar;
            }
            this.edit ? (this.edit = false) : (this.edit = true);
        },
        edydAvatar() {
            if (!this.edit) document.getElementById("prof-avatar").click();
        },
        changeAvatar(e) {
            const file = e.target.files[0];

            if (file.size > 2000000) alert("Max size 2MB!!");
            else {
                this.prof.avatar = file;
                this.sauce = URL.createObjectURL(file);
            }
        },
        update() {
            if (this.prof.password !== "" && this.prof.password.length < 8)
                this.err[0] = true;
            else if (
                this.prof.password !== "" &&
                this.prof.password !== this.prof.password_confirmation
            )
                this.err[1] = true;
            else if (this.prof.password === "" || this.prof.password !== "") {
                this.clicked = true;
                this.err = [false, false];
                let data = new FormData();

                for (var [key, val] of Object.entries(this.prof))
                    data.append(key, val);

                axios
                    .post(`/user/profile/${this.$auth.user().id}`, data, {
                        headers: { "content-type": "multipart/form-data" }
                    })
                    .then(resp => {
                        this.$auth.user(resp.data);
                        this.edyd();
                        this.clicked = false;
                    })
                    .catch(err => console.error(err.response));
            }
        }
    }
};
</script>
