<template>
    <div class="container mb-4">
        <div class="card">
            <div class="card-body">
                <div v-if="loaded">
                    <h4 class="card-title">Token</h4>
                    <div v-if="access" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Access Token</label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="access"
                                        disabled
                                    />
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" @click="copy(0)">
                                            <fa icon="copy" />
                                        </button>
                                    </div>
                                </div>
                                <small
                                    v-if="copied[0]"
                                    id="copy-acc"
                                    class="text-success"
                                    style="opacity: 1"
                                >Copied</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Secret Token</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control"
                                        v-model="secret"
                                        disabled
                                    />
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" @click="copy(1)">
                                            <fa icon="copy" />
                                        </button>
                                    </div>
                                </div>
                                <small
                                    v-if="copied[1]"
                                    id="copy-sec"
                                    class="text-success"
                                    style="opacity: 1"
                                >Copied</small>
                            </div>
                            <div class="form-group">
                                <label>Buat Secret Token Baru</label>
                                <div class="input-group">
                                    <input
                                        class="form-control"
                                        type="text"
                                        v-model="newSec"
                                        placeholder="Konfirmasi dengan mengisi email kamu"
                                    />
                                    <div class="input-group-append">
                                        <button
                                            class="btn btn-primary"
                                            :disabled="newSec !== $auth.user().email || clicked"
                                            @click="newToken()"
                                        >
                                            {{ 'Generate ' }}
                                            <fa v-if="clicked" icon="spinner" spin />
                                        </button>
                                    </div>
                                </div>
                                <small
                                    v-if="error"
                                    class="text-danger"
                                >Tunggu 1 jam untuk membuat Secret Token baru</small>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <button
                            class="btn btn-primary"
                            @click="generate()"
                            :disabled="clicked"
                        >Generate Token</button>
                        <fa v-if="clicked" icon="spinner" spin class="text-primary" />
                    </div>
                </div>
                <div v-else class="text-center">
                    <fa icon="spinner" spin size="2x" class="text-primary" />
                </div>
            </div>
        </div>
        <div class="card my-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mx-auto">
                        <h4 class="text-center">Riwayat Request</h4>
                        <div v-if="log.series[0].data.length || log.series[1].data.length">
                            <chart width="100%" type="area" :series="log.series" :options="log" />
                        </div>
                        <div v-else class="text-center text-secondary">
                            <h1>Tidak ada riwayat</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Request Berhasil</h4>
                        <table class="table table-striped">
                            <thead v-if="success.data.length">
                                <tr>
                                    <th>Request</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <thead v-else>
                                <tr colspan="2">
                                    <td class="text-center">
                                        <strong>Tidak ada request</strong>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(i, key) in success.data" :key="key">
                                    <td>
                                        <span>{{ i.description | url }}</span>
                                        <button
                                            v-if="params(i.description).length"
                                            class="btn btn-sm float-right"
                                            data-toggle="collapse"
                                            :data-target="'#success-'+key"
                                        >
                                            <fa icon="angle-down" />
                                        </button>
                                        <div
                                            :id="'success-'+key"
                                            v-if="params(i.description).length"
                                            class="collapse"
                                        >
                                            <table class="table mt-3">
                                                <tr
                                                    v-for="(j, k) in params(i.description)"
                                                    :key="k"
                                                >
                                                    <td class="py-0">{{ j.key }}</td>
                                                    <td class="py-0">{{ j.val }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td :title="i.created_at">{{ i.created_at | dt }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <paginate :data="success" @pagination-change-page="changeSuccess" />
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Request Gagal</h4>
                        <table class="table table-striped">
                            <thead v-if="failed.data.length">
                                <tr>
                                    <th>Request</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <thead v-else>
                                <tr colspan="2">
                                    <td class="text-center">
                                        <strong>Tidak ada request</strong>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(i, key) in failed.data" :key="key">
                                    <td>
                                        <span>{{ i.description | url }}</span>
                                        <button
                                            v-if="params(i.description).length"
                                            class="btn btn-sm float-right"
                                            data-toggle="collapse"
                                            :data-target="'#failed-'+key"
                                        >
                                            <fa icon="angle-down" />
                                        </button>
                                        <div
                                            :id="'failed-'+key"
                                            v-if="params(i.description).length"
                                            class="collapse"
                                        >
                                            <table class="table mt-3">
                                                <tr
                                                    v-for="(j, k) in params(i.description)"
                                                    :key="k"
                                                >
                                                    <td class="py-0">{{ j.key }}</td>
                                                    <td class="py-0">{{ j.val }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td :title="i.created_at">{{ i.created_at | dt }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <paginate :data="failed" @pagination-change-page="changeFailed" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        loaded: false,
        access: null,
        secret: null,
        clicked: false,
        copied: [false, false],
        log: {
            chart: { id: "api-log-success" },
            xaxis: {
                categories: []
            },
            tooltip: {
                y: {
                    formatter: val => parseInt(val)
                }
            },
            series: [
                {
                    name: "Request Berhasil",
                    data: []
                },
                {
                    name: "Request Gagal",
                    data: []
                }
            ],
            colors: ["#3490dc", "#e3342f"]
        },
        newSec: "",
        success: { data: [] },
        failed: { data: [] },
        error: false,
        sauce: process.env.MIX_APP_URL
    }),
    mounted() {
        axios
            .post(`token/${this.$auth.user().id}`)
            .then(resp => {
                if (resp.data.result) {
                    this.access = resp.data.token.access_token;
                    this.secret = resp.data.token.secret_token;
                }
                if (resp.data.date)
                    resp.data.date.forEach(r => {
                        const a = resp.data.log_success.filter(
                                s => s.label === r.label
                            ),
                            b = resp.data.log_error.filter(
                                e => e.label === r.label
                            );

                        this.log.xaxis.categories.push(this.dt(r.label));
                        a.length
                            ? this.log.series[0].data.push(a[0].value)
                            : this.log.series[0].data.push(0);
                        b.length
                            ? this.log.series[1].data.push(b[0].value)
                            : this.log.series[1].data.push(0);
                    });
                this.loaded = true;
            })
            .catch(err => console.error(err.response));
        axios
            .post(`dev/success/${this.$auth.user().id}`)
            .then(resp => (this.success = resp.data));

        axios
            .post(`dev/failed/${this.$auth.user().id}`)
            .then(resp => (this.failed = resp.data));
    },
    methods: {
        generate() {
            this.clicked = true;
            this.error = false;

            return axios
                .post(`token/${this.$auth.user().id}/get`)
                .then(resp => {
                    this.access = resp.data.token.access_token;
                    this.secret = resp.data.token.secret_token;
                    this.clicked = false;
                })
                .catch(err => {
                    this.error = true;
                    this.clicked = false;
                    e = false;
                });
        },
        copy(bool) {
            const txt = document.createElement("textarea");

            txt.style = "width: 0;height: 0";
            txt.value = bool ? this.secret : this.access;
            document.body.appendChild(txt);

            txt.select();
            document.execCommand("copy");

            document.body.removeChild(txt);

            var i = 1,
                interval = setInterval(() => {
                    if (bool)
                        document.getElementById("copy-sec").style.opacity = i;
                    else document.getElementById("copy-acc").style.opacity = i;
                    i -= 0.1;
                }, 200);
            this.copied = [bool ? false : true, bool ? true : false];

            setTimeout(() => {
                clearInterval(interval);
                this.copied = [false, false];
            }, 2000);
        },
        dt(str) {
            return new Date(str).toLocaleString("id-ID", {
                dateStyle: "medium"
            });
        },
        newToken() {
            this.generate(true)
                .then(() => {
                    this.copy(1);
                    this.newSec = "";
                })
                .catch(() => (this.newSec = ""));
        },
        changeSuccess(pg = 1) {
            axios
                .post(`dev/success/${this.$auth.user().id}?page=${pg}`)
                .then(resp => (this.success = resp.data));
        },
        changeFailed(pg = 1) {
            axios
                .post(`dev/failed/${this.$auth.user().id}?page=${pg}`)
                .then(resp => (this.failed = resp.data));
        },
        params(str) {
            let data = [];

            str.replace(/[?&]+([^=&]+)=([^&]*)/gi, (m, key, val) =>
                data.push({ key: key, val: val })
            );

            return data;
        }
    },
    filters: {
        dt(str) {
            return new Date(str).toLocaleString("id-ID", {
                dateStyle: "medium"
            });
        },
        url(str) {
            return str.replace(/[?&]+([^=&]+)=([^&]*)/gi, "");
        }
    }
};
</script>
