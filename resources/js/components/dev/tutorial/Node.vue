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
const fetch = require('node-fetch')

fetch('${this.sauce}/api/user', {
    headers: {
        'Authorization': 'Bearer SECRET_TOKEN',
        'Access': 'SECRET_TOKEN'
    }
})
.then(r => r.json())
.then(res => console.log(res))
.catch(err => console.error(err))
\`\`\``;
        this.txtt = `# Penjelasan
1. Install module \`npm-fetch\` dengan \`npm i npm-fetch\`.
1. Buat variabel untuk menampung module dengan \`require('node-fetch')\`.
1. Panggil variabel tersebut.
   1. Parameter pertama untuk URL yang dituju.
   1. Parameter kedua untuk header.
1. Setelah dipanggil, decode data yang diterima menjadi json.
1. Tampilkan data di console`;
    }
};
</script>
