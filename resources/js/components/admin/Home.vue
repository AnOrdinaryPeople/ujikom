<template>
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-6">
                                <h4>
                                    <fa icon="users" />
                                    {{ ' Jumlah User' }}
                                </h4>
                                {{ total[0] }}
                            </div>
                            <div class="col-6">
                                <h4>
                                    <fa icon="file-alt" />
                                    {{ ' Jumlah Postingan' }}
                                </h4>
                                {{ total[1] }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <fa icon="flag" />
                                    {{ ' Jumlah Laporan' }}
                                </h4>
                                {{ total[2] }}
                            </div>
                        </div>
                        <div class="custom-table pt-2 custom-h-250">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="3">Riwayat Hapus</th>
                                    </tr>
                                </thead>
                                <tbody v-if="del.length" class="text-left">
                                    <tr v-for="(i, key) in del" :key="key">
                                        <td>{{ i.name }}</td>
                                        <td>{{ i.email }}</td>
                                        <td>{{ i.desc | limitDesc }}</td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="3">
                                            <h6>Tidak ada riwayat</h6>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="text-center">
                    <h4>Laporan</h4>
                    <small class="text-muted">Jumlah laporan yang dilakukan user</small>
                </div>
                <chart
                    v-if="report.series[0].data.length"
                    width="100%"
                    type="line"
                    :options="report"
                    :series="report.series"
                />
            </div>
        </div>
        <div class="row py-4">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="row-6">
                    <div class="text-center">
                        <h4>Postingan</h4>
                        <small class="text-muted">Jumlah postingan yang ditambah user</small>
                    </div>
                    <chart
                        v-if="post.series[0].data.length"
                        width="100%"
                        type="bar"
                        :options="post"
                        :series="post.series"
                    />
                </div>
                <div class="row-6 my-4">
                    <div class="text-center">
                        <h4>Jumlah Akun</h4>
                        <small class="text-muted">Jumlah seluruh akun user</small>
                    </div>
                    <chart
                        v-if="user.series[0].data.length"
                        width="100%"
                        type="area"
                        :series="user.series"
                        :options="user"
                    />
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="row-6">
                    <div class="text-center">
                        <h4>Jenis Postingan</h4>
                        <small class="text-muted">Total jenis postingan yang ditambah user</small>
                    </div>
                    <chart
                        v-if="type.series.length"
                        width="100%"
                        type="pie"
                        :series="type.series"
                        :options="type"
                    />
                </div>
                <div class="row-6 my-4">
                    <div class="text-center">
                        <small class="text-muted">Jumlah jenis postingan dalam 1 bulan</small>
                    </div>
                    <chart
                        v-if="typee.series[0].data.length"
                        width="100%"
                        type="line"
                        :options="typee"
                        :series="typee.series"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        post: {
            chart: { id: "admin-post" },
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
                    name: "Postingan",
                    data: []
                }
            ]
        },
        type: {
            chart: { id: "admin-type" },
            series: [],
            labels: [],
            tooltip: {
                y: {
                    formatter: val => parseInt(val)
                }
            }
        },
        report: {
            chart: { id: "admin-report" },
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
                    name: "Laporan",
                    data: []
                }
            ]
        },
        user: {
            chart: { id: "admin-user" },
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
                    name: "Total Akun",
                    data: []
                }
            ]
        },
        typee: {
            chart: { id: "admin-t" },
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
                    name: "Postingan",
                    data: []
                },
                {
                    name: "Artikel",
                    data: []
                },
                {
                    name: "Rekomendasi",
                    data: []
                }
            ]
        },
        total: [0, 0, 0],
        del: []
    }),
    mounted() {
        axios
            .post(`admin/home`)
            .then(resp => {
                var r = resp.data;
                r.post.forEach(p => {
                    this.post.series[0].data.push(p.value);
                    this.post.xaxis.categories.push(
                        new Date(p.label).toLocaleString("id-ID", {
                            dateStyle: "medium"
                        })
                    );
                    this.typee.xaxis.categories.push(
                        new Date(p.label).toLocaleString("id-ID", {
                            dateStyle: "medium"
                        })
                    );
                });
                r.type.forEach(t => {
                    this.type.series.push(t.value);
                    this.type.labels.push(
                        t.label === 1
                            ? "Postingan"
                            : t.label === 2
                            ? "Artikel"
                            : "Rekomendasi"
                    );
                });
                for (var i = 0; i < 3; i++) {
                    var t = this.typee.series[i],
                        type = r.typee[i];

                    for (var j = 0; j < r.post.length; j++) {
                        var e = type.filter(f => f.label === r.post[j].label);

                        if (e.length) t.data[j] = e[0].value;
                        else t.data[j] = 0;
                    }
                }
                r.report.forEach(r => {
                    this.report.series[0].data.push(r.value);
                    this.report.xaxis.categories.push(
                        new Date(r.label).toLocaleString("id-ID", {
                            dateStyle: "medium"
                        })
                    );
                });
                r.user.forEach(r => {
                    this.user.series[0].data.push(r.value);
                    this.user.xaxis.categories.push(
                        new Date(r.label).toLocaleString("id-ID", {
                            dateStyle: "medium"
                        })
                    );
                });
                this.total = r.total;
                this.del = r.del;
            })
            .catch(err => console.error(err));
    },
    filters: {
        limitDesc(str) {
            return str.length > 15 ? str.substring(0, 15) + "..." : str;
        }
    }
};
</script>
