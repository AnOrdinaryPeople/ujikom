<template>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <markdown class="mb-body" :content="txtt" />
                </div>
                <div class="col-6">
                    <markdown class="mb-body" :content="txt" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        sauce: process.env.MIX_APP_URL,
        txt: "",
        txtt: ""
    }),
    created() {
        this.txt = `\`\`\`js
$.ajax({
    url: '${this.sauce}/api/user',
    data: {
        limit: 15,
        skip: 2,
        sort_by: 'name',
        sort: 'desc'
    },
    beforeSend: function(b){
        b.setRequestHeader('Authorization', 'Bearer SECRET_TOKEN');
        b.setRequestHeader('Access', 'ACCESS_TOKEN');
    },
    success: function(s){
        console.log(s);
    },
    error: function(e){
        console.error(e);
    }
});
\`\`\``;
        this.txtt = `# Penjelasan
1. \`$.ajax()\` fungsi untuk melakukan request ke server.
1. \`url\` request ke URL yang dituju.
1. \`data\` data yang akan diberikan ke URL yang dituju.
1. \`beforeSend\` proses sebelum melakukan pengiriman
   1. \`setRequestHeader\` membuat header untuk mengakses URL yang dituju.
1. \`success\` proses setelah menerima request.
1. \`error\` proses gagal, Ada kesalahan dibagian data, beforeSend, atau yang lain.`;
    }
};
</script>
