<template>
    <div class="container">
        <div class="row">
            <div id="top-of-detail" class="col-lg-8 col-md-8 col-sm-12 pt-3">
                <div v-if="p.id !== 0" class="card">
                    <div class="card-header border-0 bg-white">
                        <div class="d-none">{{ isVotedPost(p.id, 'detail', p.type) }}</div>
                        <div class="row pb-0">
                            <div class="col-1 text-center p-0">
                                <fa
                                    class="btn-vote-up"
                                    :class="p.temp == 1 ? 'text-success' : ''"
                                    icon="caret-up"
                                    size="2x"
                                    @click="vote(p.id, p.type, p.temp == 1 ? 0 : 1, 1)"
                                />
                                <p
                                    class="p-0 m-0"
                                    :class="p.temp === 1 ? 'text-success' : (p.temp === -1 ? 'text-danger' : '')"
                                    :title="p.votes"
                                >{{ p.votes | voteFilter }}</p>
                                <fa
                                    class="btn-vote-down"
                                    :class="p.temp == -1 ? 'text-danger' : ''"
                                    icon="caret-down"
                                    size="2x"
                                    @click="vote(p.id, p.type, p.temp == -1 ? 0 : -1, 0)"
                                />
                            </div>
                            <div class="col-10">
                                <h4 class="mb-0">{{ p.title | titleCase }}</h4>
                                <small
                                    class="text-muted"
                                >{{ p.created_at !== p.updated_at ? '(edited)' : '' }}</small>
                            </div>
                            <div class="col-1">
                                <div v-if="$auth.check()" class="dropdown float-right">
                                    <button class="btn" data-toggle="dropdown">
                                        <fa icon="ellipsis-v" />
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a
                                            v-if="p.type === 1 && $auth.user().id !== p.user_id && warn.title === ''"
                                            class="dropdown-item"
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#modal-warning"
                                            @click.prevent
                                        >
                                            <fa icon="exclamation-triangle" />
                                            {{ ' Beri Peringatan' }}
                                        </a>
                                        <a
                                            v-if="$auth.user().id !== p.user_id"
                                            class="dropdown-item"
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#modal-report"
                                            @click.prevent
                                        >
                                            <fa icon="flag" />
                                            {{ ' Laporkan' }}
                                        </a>
                                        <router-link
                                            v-if="$auth.user().id === p.user_id"
                                            class="dropdown-item"
                                            :to="`/home/edit/${p.id}`"
                                        >
                                            <fa icon="pen" />
                                            {{ ' Edit' }}
                                        </router-link>
                                        <a
                                            v-if="$auth.user().id === p.user_id"
                                            class="dropdown-item"
                                            data-toggle="modal"
                                            data-target="#modal-detail-post"
                                            href="#"
                                            @click.prevent
                                        >
                                            <fa icon="trash" />
                                            {{ ' Hapus' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div v-if="warn.title !== ''" class="alert alert-warning">
                            <div
                                v-if="$auth.check() && $auth.user().id === warn.user_id"
                                class="dropdown float-right"
                            >
                                <button class="btn" data-toggle="dropdown">
                                    <fa icon="ellipsis-v" />
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a
                                        class="dropdown-item"
                                        data-toggle="modal"
                                        data-target="#modal-warn-edit"
                                        href="#"
                                        @click.prevent="open('warnEdit', 'top-of-detail')"
                                    >
                                        <fa icon="pen" />
                                        {{ ' Edit' }}
                                    </a>
                                    <a
                                        class="dropdown-item"
                                        data-toggle="modal"
                                        data-target="#modal-warn-alert"
                                        href="#"
                                    >
                                        <fa icon="trash" />
                                        {{ ' Hapus' }}
                                    </a>
                                </div>
                            </div>
                            <h4 class="mb-0">{{ warn.title }}</h4>
                            <small>Peringatan dari {{ warn.name }}</small>
                            <markdown
                                class="mt-2 mb-body custom-text-warning"
                                :content="warn.desc"
                            />
                        </div>
                        <h5 v-if="p.type == 3" class="text-muted">{{ p.location }}</h5>
                        <markdown class="mb-body" :content="p.desc" />
                    </div>
                    <div class="card-footer border-0 custom-bg-none">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    {{ p.created_at | dt }}
                                    <br />
                                    <small>{{ p.type == 1 ? 'Postingan' : (p.type == 2 ? 'Artikel' : 'Rekomendasi Perusahaan') }}</small>
                                </div>
                                <div class="col-5 px-0 mx-0 pt-2 text-right">{{ p.name }}</div>
                                <div class="col-1 custom-img">
                                    <img class="rounded-circle img-responsive" :src="p.avatar" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <post-load :detail="true" />
                </div>
                <h4 id="answer-top" class="mt-4">{{ p.type == 1 ? 'Jawaban' : 'Komentar' }}</h4>
                <div v-if="best" id="detail-best" class="card mb-4 border-success">
                    <div class="d-none">{{ isVotedPost(best.id, 'best') }}</div>
                    <div class="card-header border-0 custom-bg-none">
                        <div class="row pb-0">
                            <div class="col-1 text-center p-0">
                                <fa
                                    class="btn-vote-up"
                                    :class="best.temp == 1 ? 'text-success' : ''"
                                    icon="caret-up"
                                    size="2x"
                                    @click="vote(best.id, 4, best.temp == 1 ? 0 : 1, 1, 'best')"
                                />
                                <p
                                    class="p-0 m-0"
                                    :class="best.temp === 1 ? 'text-success' : (best.temp === -1 ? 'text-danger' : '')"
                                    :title="best.votes"
                                >{{ best.votes | voteFilter }}</p>
                                <fa
                                    class="btn-vote-down"
                                    :class="best.temp == -1 ? 'text-danger' : ''"
                                    icon="caret-down"
                                    size="2x"
                                    @click="vote(best.id, 4, best.temp == -1 ? 0 : -1, 0, 'best')"
                                />
                            </div>
                            <div
                                :class="$auth.check() && best.user_id === $auth.user().id ? 'col-10' : 'col-11'"
                            >
                                <div class="row">
                                    <div class="col-9">
                                        <div class="row">
                                            <div class="col-1 custom-img text-center">
                                                <img
                                                    class="rounded-circle img-responsive"
                                                    :src="best.avatar"
                                                />
                                            </div>
                                            <div class="col-11 pl-4 pt-2">
                                                {{ best.name + ' ' }}
                                                <small
                                                    class="text-muted font-italic"
                                                >/ {{ best.created_at | dt }} {{ best.created_at !== best.updated_at ? '(edited)' : '' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-right">
                                        <fa class="text-success" icon="check" size="2x" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-1">
                                <div
                                    v-if="$auth.check() && best.user_id === $auth.user().id"
                                    class="dropdown float-right"
                                >
                                    <button class="btn" data-toggle="dropdown">
                                        <fa icon="ellipsis-v" />
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a
                                            v-if="$auth.user().id === best.user_id"
                                            class="dropdown-item"
                                            data-toggle="modal"
                                            data-target="#modal-detail-edit"
                                            href="#"
                                            @click.prevent="open('bestt', 'answer-top')"
                                        >
                                            <fa icon="pen" />
                                            {{ ' Edit' }}
                                        </a>
                                        <a
                                            v-if="$auth.user().id === best.user_id"
                                            class="dropdown-item"
                                            data-toggle="modal"
                                            data-target="#modal-detail-alert"
                                            href="#"
                                            @click.prevent="open('bestt', 'answer-top')"
                                        >
                                            <fa icon="trash" />
                                            {{ ' Hapus' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <markdown class="mb-body" :content="best.desc" />
                        <div v-if="child.length" class="container mt-4">
                            <hr />
                            <div v-for="(i, k) in child" :key="k" class="card border-0">
                                <div class="d-none">{{ isVotedPost(i.id, 'childB', 4, k) }}</div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-1 custom-img text-center">
                                            <img
                                                class="rounded-circle img-responsive"
                                                :src="i.avatar"
                                            />
                                        </div>
                                        <div class="col-10 pl-4 pt-2">
                                            {{ i.name + ' ' }}
                                            <small
                                                class="text-muted font-italic"
                                            >/ {{ i.created_at | dt }} {{ i.created_at !== i.updated_at ? '(edited)' : '' }}</small>
                                        </div>
                                        <div class="col-1">
                                            <div
                                                v-if="$auth.check() && $auth.user().id === i.user_id"
                                                class="dropdown float-right"
                                            >
                                                <button class="btn" data-toggle="dropdown">
                                                    <fa icon="ellipsis-v" />
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a
                                                        class="dropdown-item"
                                                        data-toggle="modal"
                                                        data-target="#modal-detail-edit"
                                                        href="#"
                                                        @click.prevent="open('childB', 'answer-top', k)"
                                                    >
                                                        <fa icon="pen" />
                                                        {{ ' Edit' }}
                                                    </a>
                                                    <a
                                                        class="dropdown-item"
                                                        data-toggle="modal"
                                                        data-target="#modal-detail-alert"
                                                        href="#"
                                                        @click.prevent="open('childB', 'answer-top', k)"
                                                    >
                                                        <fa icon="trash" />
                                                        {{ ' Hapus' }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <markdown class="mb-body mt-2" :content="i.desc" />
                                    <div class="text-right mt-4" :title="i.votes">
                                        <fa
                                            class="btn-vote-up"
                                            :class="i.temp == 1 ? 'text-success' : ''"
                                            icon="caret-up"
                                            size="lg"
                                            @click="vote(i.id, 4, i.temp == 1 ? 0 : 1, 1, 'childB', k)"
                                        />
                                        <span
                                            class="p-0 m-0"
                                            :class="i.temp === 1 ? 'text-success' : (i.temp === -1 ? 'text-danger' : '')"
                                            :title="i.votes"
                                        >{{ i.votes | voteFilter }}</span>
                                        <fa
                                            class="btn-vote-down"
                                            :class="i.temp == -1 ? 'text-danger' : ''"
                                            icon="caret-down"
                                            size="lg"
                                            @click="vote(i.id, 4, i.temp == -1 ? 0 : -1, 0, 'childB', k)"
                                        />
                                    </div>
                                    <hr />
                                </div>
                            </div>
                        </div>
                        <div v-if="$auth.check()">
                            <div class="text-right my-2">
                                <small
                                    class="text-muted custom-hover"
                                    data-toggle="collapse"
                                    data-target="#best-reply"
                                >Balas Komentar</small>
                            </div>
                            <invut
                                id="best-reply"
                                class="collapse"
                                :sauce="`/user/post/reply/child/${p.id}/${$auth.user().id}/${best.user_id}/${best.id}`"
                                pg="reply"
                                @reply="updateBest"
                            />
                        </div>
                        <div class="text-center">
                            <button
                                v-if="child.length >= 5 && bestCanLoad"
                                class="btn btn-outline-secondary"
                                @click="loadmore('best', best.id)"
                                :disabled="clicked"
                            >
                                <span v-if="clicked">
                                    <fa icon="spinner" spin size="lg" class="text-secondary" />
                                </span>
                                <span v-else>Load more</span>
                            </button>
                        </div>
                    </div>
                </div>
                <hr />
                <div id="detail-answer">
                    <div v-if="r.data.length">
                        <div
                            v-for="(i, key) in r.data"
                            :key="key"
                            :id="'reply-'+i.id"
                            class="card mb-2"
                        >
                            <div class="d-none">{{ isVotedPost(i.id, 'rep', 4, key) }}</div>
                            <div class="card-header border-0 custom-bg-none">
                                <div class="row pb-0">
                                    <div class="col-1 text-center p-0">
                                        <fa
                                            class="btn-vote-up"
                                            :class="i.temp == 1 ? 'text-success' : ''"
                                            icon="caret-up"
                                            size="2x"
                                            @click="vote(i.id, 4, i.temp == 1 ? 0 : 1, 1, 'rep', key)"
                                        />
                                        <p
                                            class="p-0 m-0"
                                            :class="i.temp === 1 ? 'text-success' : (i.temp === -1 ? 'text-danger' : '')"
                                            :title="i.votes"
                                        >{{ i.votes | voteFilter }}</p>
                                        <fa
                                            class="btn-vote-down"
                                            :class="i.temp == -1 ? 'text-danger' : ''"
                                            icon="caret-down"
                                            size="2x"
                                            @click="vote(i.id, 4, i.temp == -1 ? 0 : -1, 0, 'rep', key)"
                                        />
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="row">
                                                    <div class="col-1 custom-img text-center">
                                                        <img
                                                            class="rounded-circle img-responsive"
                                                            :src="i.avatar"
                                                        />
                                                    </div>
                                                    <div class="col-10 pl-4 pt-2">
                                                        {{ i.name + ' ' }}
                                                        <small
                                                            class="text-muted font-italic"
                                                        >/ {{ i.created_at | dt }} {{ i.created_at !== i.updated_at ? '(edited)' : '' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div
                                            v-if="$auth.check() && i.user_id === $auth.user().id || $auth.check() && p.user_id === $auth.user().id && p.type == 1"
                                            class="dropdown float-right"
                                        >
                                            <button class="btn" data-toggle="dropdown">
                                                <fa icon="ellipsis-v" />
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a
                                                    v-if="p.type === 1 && p.user_id === $auth.user().id"
                                                    class="dropdown-item"
                                                    href="#"
                                                    data-toggle="modal"
                                                    data-target="#modal-detail-best"
                                                    @click.prevent="open('best', 'detail-answer', key)"
                                                >
                                                    <fa icon="check" />
                                                    {{ ' Tandai jawaban terbaik' }}
                                                </a>
                                                <a
                                                    v-if="$auth.user().id === i.user_id"
                                                    class="dropdown-item"
                                                    data-toggle="modal"
                                                    data-target="#modal-detail-edit"
                                                    href="#"
                                                    @click.prevent="open('rep', 'reply-' + i.id, key)"
                                                >
                                                    <fa icon="pen" />
                                                    {{ ' Edit' }}
                                                </a>
                                                <a
                                                    v-if="$auth.user().id === i.user_id"
                                                    class="dropdown-item"
                                                    data-toggle="modal"
                                                    data-target="#modal-detail-alert"
                                                    href="#"
                                                    @click.prevent="open('rep', 'reply-' + i.id, key)"
                                                >
                                                    <fa icon="trash" />
                                                    {{ ' Hapus' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <markdown class="mb-body" :content="i.desc" />
                                <div v-if="i.has_child.length" class="container mt-4">
                                    <hr />
                                    <div
                                        v-for="(j, k) in i.has_child"
                                        :key="k"
                                        class="card border-0"
                                    >
                                        <div
                                            class="d-none"
                                        >{{ isVotedPost(j.id, 'child', 4, key, k) }}</div>
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-1 custom-img text-center">
                                                    <img
                                                        class="rounded-circle img-responsive"
                                                        :src="j.avatar"
                                                    />
                                                </div>
                                                <div class="col-11 pl-4 pt-2">
                                                    {{ j.name + ' ' }}
                                                    <small
                                                        class="text-muted font-italic"
                                                    >/ {{ j.created_at | dt }} {{ j.created_at !== j.updated_at ? '(edited)' : '' }}</small>
                                                    <div
                                                        v-if="$auth.check() && $auth.user().id === j.user_id"
                                                        class="dropdown float-right"
                                                    >
                                                        <button class="btn" data-toggle="dropdown">
                                                            <fa icon="ellipsis-v" />
                                                        </button>
                                                        <div
                                                            class="dropdown-menu dropdown-menu-right"
                                                        >
                                                            <a
                                                                class="dropdown-item"
                                                                data-toggle="modal"
                                                                data-target="#modal-detail-edit"
                                                                href="#"
                                                                @click.prevent="open('child', 'reply-' + i.id, key, k)"
                                                            >
                                                                <fa icon="pen" />
                                                                {{ ' Edit' }}
                                                            </a>
                                                            <a
                                                                class="dropdown-item"
                                                                data-toggle="modal"
                                                                data-target="#modal-detail-alert"
                                                                href="#"
                                                                @click.prevent="open('child', 'reply-' + i.id, key, k)"
                                                            >
                                                                <fa icon="trash" />
                                                                {{ ' Hapus' }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <markdown class="mb-body mt-2" :content="j.desc" />
                                            <div class="text-right" :title="j.votes">
                                                <fa
                                                    class="btn-vote-up"
                                                    :class="j.temp == 1 ? 'text-success' : ''"
                                                    icon="caret-up"
                                                    size="lg"
                                                    @click="vote(j.id, 4, j.temp == 1 ? 0 : 1, 1, 'child', key, k)"
                                                />
                                                <span
                                                    class="p-0 m-0"
                                                    :class="j.temp === 1 ? 'text-success' : (j.temp === -1 ? 'text-danger' : '')"
                                                    :title="j.votes"
                                                >{{ j.votes | voteFilter }}</span>
                                                <fa
                                                    class="btn-vote-down"
                                                    :class="j.temp == -1 ? 'text-danger' : ''"
                                                    icon="caret-down"
                                                    size="lg"
                                                    @click="vote(j.id, 4, j.temp == -1 ? 0 : -1, 0, 'child', key, k)"
                                                />
                                            </div>
                                            <hr />
                                        </div>
                                    </div>
                                </div>
                                <div v-if="$auth.check()">
                                    <div class="text-right my-2">
                                        <small
                                            class="text-muted custom-hover"
                                            data-toggle="collapse"
                                            :data-target="'#answer-'+i.id"
                                        >Balas Komentar</small>
                                    </div>
                                    <invut
                                        :id="'answer-'+i.id"
                                        :idd="i.id"
                                        class="collapse"
                                        :sauce="`/user/post/reply/child/${p.id}/${$auth.user().id}/${i.user_id}/${i.id}`"
                                        pg="reply"
                                        @reply="updateChild"
                                    />
                                </div>
                                <div class="text-center">
                                    <button
                                        v-if="i.has_child.length >= 5 && replySkip[key].canLoad"
                                        class="btn btn-outline-secondary"
                                        @click="loadmore('rep', i.id, key)"
                                        :disabled="clicked"
                                    >
                                        <span v-if="clicked">
                                            <fa
                                                icon="spinner"
                                                spin
                                                size="lg"
                                                class="text-secondary"
                                            />
                                        </span>
                                        <span v-else>Load more</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="loader" class="text-center text-secondary">
                            <fa class="mb-2" icon="sad-cry" size="5x" />
                            <h1>Tidak ada {{ p.type == 1 ? 'jawaban' : 'komentar' }}</h1>
                        </div>
                        <div v-else>
                            <post-load :reply="true" />
                        </div>
                    </div>
                </div>
                <div v-if="$auth.check()" id="leave-reply" class="mt-4">
                    <h3>Tambah {{ p.type == 1 ? 'Jawaban' : 'Komentar' }}</h3>
                    <invut
                        :sauce="`/user/post/reply/${p.id}/${$auth.user().id}/${p.user_id}`"
                        pg="reply"
                        @reply="updateReply"
                    />
                </div>
                <div v-else class="text-center">
                    <h1>
                        <router-link class="text-decoration-none" to="/login">Login</router-link>
                        {{ ' untuk' }} menambah komentar
                    </h1>
                    <h4>
                        Tidak punya akun? segera
                        <router-link class="text-decoration-none" to="/register">daftar</router-link>!
                    </h4>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="sticky-top pt-3">
                    <div v-if="$auth.check()" class="card">
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item custom-hover" @click="toAdd()">
                                <fa :icon="['fas', 'angle-down']" />
                                {{ ' Ke tambah ' + (p.type == 1 ? 'jawaban' : 'komentar') }}
                            </li>
                        </ul>
                    </div>
                    <div v-if="r.data.length" class="card" :class="$auth.check() ? 'my-3' : 'mb-3'">
                        <div
                            class="card-header text-center"
                        >Urutkan {{ p.type == 1 ? 'Jawaban' : 'Komentar' }}</div>
                        <ul class="list-group list-group-flush">
                            <li
                                v-for="(i, key) in sort"
                                :key="key"
                                class="list-group-item custom-hover"
                                :class="key === sortKey ? 'text-primary' : ''"
                                @click="orderBy(key)"
                            >
                                <fa :icon="i.icon" size="lg" />
                                {{ ' ' + i.name }}
                            </li>
                        </ul>
                    </div>
                    <paginate :data="r" @pagination-change-page="changePage" />
                </div>
            </div>
        </div>
        <div v-if="$auth.check()">
            <modal
                idd="modal-detail-post"
                type="danger"
                :ttl="p.title"
                :sauce="`user/destroy/${p.id}`"
            />
            <modal idd="modal-detail-edit" type="edit" ref="modalEdit" @modalEdyd="editReply" />
            <modal
                idd="modal-detail-alert"
                type="alert"
                :sauce="`user/destroy/reply/${modalInfo.id}`"
                @modalEdyd="editReply"
            />
            <modal
                v-if="p.user_id === $auth.user().id"
                idd="modal-detail-best"
                type="best"
                :sauce="`user/best/${modalInfo.id}/${$route.params.id}/${$auth.user().id}`"
                @modalEdyd="updateBestt"
            />
            <modal
                idd="modal-warning"
                type="warning"
                :sauce="`user/warning/${p.id}/${$auth.user().id}`"
                @modalEdyd="updateWarn"
            />
            <modal
                idd="modal-warn-edit"
                type="warning"
                ttl="Edit Peringatan"
                :sauce="`user/warn/edit/${warn.id}/${p.id}`"
                @modalEdyd="updateWarn"
            />
            <modal
                idd="modal-warn-alert"
                type="warnDel"
                :sauce="`user/warn/del/${warn.id}/${p.id}`"
                @modalEdyd="updateWarn"
            />
            <modal
                idd="modal-report"
                type="report"
                :sauce="`user/report/${p.id}/${$auth.user().id}`"
            />
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        p: {
            id: 0,
            type: 1,
            votes: 0,
            title: "",
            name: "",
            created_at: "2020-01-01",
            location: "",
            desc: "",
            avatar: "",
            temp: 0
        },
        voted: [],
        cVote: [],
        r: { data: [] },
        best: 0,
        child: [],
        loader: false,
        sort: [
            { name: "Terbaru", icon: "sort-amount-up" },
            { name: "Terakhir", icon: "sort-amount-up-alt" },
            { name: "Vote Terbesar", icon: "sort-numeric-up-alt" },
            { name: "Vote Terkecil", icon: "sort-numeric-up" }
        ],
        sortKey: 0,
        once: {
            detail: true,
            best: true,
            childB: true,
            rep: true,
            child: true
        },
        modalInfo: {},
        warn: {
            id: 0,
            title: "",
            desc: "",
            name: "",
            user_id: 0
        },
        bestSkip: 5,
        bestCanLoad: true,
        replySkip: [],
        clicked: false
    }),
    mounted() {
        axios
            .post(
                `user/home/detail/${this.$route.params.id}/${
                    this.$auth.user().id
                }`
            )
            .then(resp => {
                this.p = resp.data.post;
                this.voted = resp.data.voted;
                this.cVote = resp.data.child_vote;
                this.best = resp.data.best;
                this.child = resp.data.child_best;
                resp.data.warn ? (this.warn = resp.data.warn) : "";
                this.loader = true;
            });
        this.$route.query.page !== undefined
            ? this.changePage(this.$route.query.page)
            : this.changePage();
    },
    methods: {
        vote(id, type, v, btn, t = null, key = 0, child = 0) {
            if (this.$auth.check()) {
                switch (t) {
                    case "best":
                        this.best.temp = v;
                        break;
                    case "childB":
                        this.child[key].temp = v;
                        break;
                    case "rep":
                        this.r.data[key].temp = v;
                        break;
                    case "child":
                        this.r.data[key].has_child[child].temp = v;
                        break;
                    default:
                        this.p.temp = v;
                        break;
                }

                axios
                    .post(`/user/vote/${id}/${type}/${this.$auth.user().id}`, {
                        vote: v,
                        parent: this.$route.params.id
                    })
                    .then(resp => {
                        this.voted = resp.data;

                        if (v === 0) {
                            if (btn === 1) {
                                switch (t) {
                                    case "best":
                                        this.best.votes -= 1;
                                        break;
                                    case "childB":
                                        this.child[key].votes -= 1;
                                        break;
                                    case "rep":
                                        this.r.data[key].votes -= 1;
                                        break;
                                    case "child":
                                        this.r.data[key].has_child[
                                            child
                                        ].votes -= 1;
                                        break;
                                    default:
                                        this.p.votes -= 1;
                                        break;
                                }
                            } else {
                                switch (t) {
                                    case "best":
                                        this.best.votes += 1;
                                        break;
                                    case "childB":
                                        this.child[key].votes += 1;
                                        break;
                                    case "rep":
                                        this.r.data[key].votes += 1;
                                        break;
                                    case "child":
                                        this.r.data[key].has_child[
                                            child
                                        ].votes += 1;
                                        break;
                                    default:
                                        this.p.votes += 1;
                                        break;
                                }
                            }
                        } else {
                            switch (t) {
                                case "best":
                                    this.best.votes += v;
                                    break;
                                case "childB":
                                    this.child[key].votes += v;
                                    break;
                                case "rep":
                                    this.r.data[key].votes += v;
                                    break;
                                case "child":
                                    this.r.data[key].has_child[
                                        child
                                    ].votes += v;
                                    break;
                                default:
                                    this.p.votes += v;
                                    break;
                            }
                        }
                    })
                    .catch(err => console.error(err.response));
            }
        },
        isVotedPost(id, typee, type = 4, key = 0, child = 0) {
            if (this.$auth.check()) {
                if (this.voted.length || this.cVote.length) {
                    const v = this.voted.filter(
                            e =>
                                e.vote !== 0 &&
                                e.type_id === id &&
                                e.type === type &&
                                e.user_id === this.$auth.user().id
                        ),
                        vv = this.cVote.filter(
                            e =>
                                e.vote !== 0 &&
                                e.type_id === id &&
                                e.type === type &&
                                e.user_id === this.$auth.user().id
                        );

                    switch (typee) {
                        case "detail":
                            this.once.detail && v.length
                                ? (this.p.temp = v[0].vote)
                                : "";
                            this.once.detail = false;
                            break;
                        case "best":
                            this.once.best && vv.length
                                ? Vue.set(
                                      this.best,
                                      "temp",
                                      this.best.temp || vv[0].vote
                                  )
                                : "";
                            this.once.best = false;
                            break;
                        case "childB":
                            this.once.childB && vv.length
                                ? Vue.set(
                                      this.child[key],
                                      "temp",
                                      this.best[key].temp || vv[0].vote
                                  )
                                : "";
                            key === this.child.length - 1
                                ? (this.once.childB = false)
                                : "";
                            break;
                        case "rep":
                            this.once.rep && vv.length
                                ? Vue.set(
                                      this.r.data[key],
                                      "temp",
                                      this.r.data[key].temp || vv[0].vote
                                  )
                                : "";
                            key === this.r.data.length - 1
                                ? (this.once.rep = false)
                                : "";
                            break;
                        case "child":
                            this.once.child && vv.length
                                ? Vue.set(
                                      this.r.data[key].has_child[child],
                                      "temp",
                                      this.r.data[key].has_child[child].temp ||
                                          vv[0].vote
                                  )
                                : "";
                            key === this.r.data[key].has_child.length - 1
                                ? (this.once.child = false)
                                : "";
                            break;

                        default:
                            break;
                    }
                }
            }

            return;
        },
        loadmore(type, id, key = 0) {
            this.clicked = true;
            axios
                .post(
                    `user/home/detail/child/more/${id}/${
                        type === "best"
                            ? this.bestSkip
                            : this.replySkip[key].skip
                    }`
                )
                .then(resp => {
                    if (resp.data.length) {
                        if (type === "best") {
                            this.child.push(...resp.data);
                            this.bestSkip += 5;
                        } else {
                            this.r.data[key].has_child.push(...resp.data);
                            this.replySkip[key].skip += 5;
                        }
                    } else {
                        if (type === "best") this.bestCanLoad = false;
                        else this.replySkip[key].canLoad = false;
                    }
                    this.clicked = false;
                });
        },
        changePage(page = 1, toId = null) {
            axios
                .post(
                    `user/reply/${this.$route.params.id}/${
                        this.$auth.user().id
                    }?page=${page}`
                )
                .then(resp => {
                    this.r = resp.data.reply;
                    this.voted = resp.data.voted;
                    this.cVote = resp.data.child_vote;

                    this.$router
                        .replace({
                            query: {
                                ...this.$route.query,
                                page: page
                            }
                        })
                        .catch(err => {});
                    this.orderBy(this.sortKey);
                    this.once.rep = true;
                    this.once.child = true;
                    if (this.r.data.length)
                        for (var i = 0; i < this.r.data.length; i++)
                            Vue.set(this.replySkip, i, {
                                canLoad: true,
                                skip: 5
                            });
                    if (toId) {
                        document
                            .getElementById("reply-" + toId)
                            .scrollIntoView();
                        $("#answer-" + toId).collapse("hide");
                    }
                })
                .catch(err => console.error(err));
        },
        orderBy(key) {
            this.sortKey = key;
            switch (key) {
                case 0:
                    return this.r.data.sort((a, b) =>
                        a.id < b.id ? 1 : b.id < a.id ? -1 : 0
                    );
                    break;
                case 1:
                    return this.r.data.sort((a, b) =>
                        a.id > b.id ? 1 : b.id > a.id ? -1 : 0
                    );
                    break;
                case 2:
                    return this.r.data.sort((a, b) =>
                        a.votes < b.votes ? 1 : b.votes < a.votes ? -1 : 0
                    );
                    break;
                case 3:
                    return this.r.data.sort((a, b) =>
                        a.votes > b.votes ? 1 : b.votes > a.votes ? -1 : 0
                    );
                    break;

                default:
                    break;
            }
        },
        updateReply() {
            document.getElementById("detail-answer").scrollIntoView();
            this.changePage(1);
        },
        updateBest() {
            document.getElementById("detail-best").scrollIntoView();
            $("#best-reply").collapse("hide");
            axios
                .post(
                    `user/home/detail/${this.$route.params.id}/${
                        this.$auth.user().id
                    }`
                )
                .then(resp => {
                    this.best = resp.data.best;
                    this.child = resp.data.child_best;
                })
                .catch(err => console.error(err.response));
        },
        updateChild(data) {
            this.changePage(data.paginate, data.id);
        },
        editReply(data) {
            document.getElementById(data.id).scrollIntoView();
            this.changePage(data.paginate);
            axios
                .post(
                    `user/home/detail/${this.$route.params.id}/${
                        this.$auth.user().id
                    }`
                )
                .then(resp => {
                    this.best = resp.data.best;
                    this.child = resp.data.child_best;
                })
                .catch(err => console.error(err.response));
        },
        updateBestt(data) {
            this.best = data.best;
            this.child = data.child;
            this.once.best = true;
            document.getElementById(data.id).scrollIntoView();
            this.changePage(data.paginate);
        },
        toAdd() {
            document.getElementById("leave-reply").scrollIntoView();
        },
        updateWarn(data) {
            console.log(data);
            this.warn = {
                title: data[0],
                desc: data[1],
                name: data[2],
                user_id: data[3],
                id: data[4]
            };
        },
        open(type, id, key = 0, child = 0) {
            switch (type) {
                case "rep":
                case "best":
                    this.modalInfo = this.r.data[key];
                    break;
                case "child":
                    this.modalInfo = this.r.data[key].has_child[child];
                    break;
                case "childB":
                    this.modalInfo = this.child[key];
                    break;
                case "bestt":
                    this.modalInfo = this.best;
                    break;

                default:
                    break;
            }
            this.$refs.modalEdit.setObj(this.modalInfo, id);
        }
    },
    filters: {
        titleCase(str) {
            return str.replace(/\w\S*/g, txt => {
                return (
                    txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
                );
            });
        },
        dt(str) {
            return new Date(str).toLocaleString("id-ID", {
                dateStyle: "medium"
            });
        },
        voteFilter(n) {
            const si = [
                    { value: 1e18, symbol: "E" },
                    { value: 1e15, symbol: "P" },
                    { value: 1e12, symbol: "T" },
                    { value: 1e9, symbol: "G" },
                    { value: 1e6, symbol: "M" },
                    { value: 1e3, symbol: "k" }
                ],
                msi = [
                    { value: -1e18, symbol: "E" },
                    { value: -1e15, symbol: "P" },
                    { value: -1e12, symbol: "T" },
                    { value: -1e9, symbol: "G" },
                    { value: -1e6, symbol: "M" },
                    { value: -1e3, symbol: "k" }
                ];

            for (var i = 0; i < si.length; i++) {
                if (n >= si[i].value)
                    return (
                        (n / si[i].value).toFixed(1).replace(/\.?0+$/, "") +
                        si[i].symbol
                    );
                else if (n <= msi[i].value)
                    return (
                        "-" +
                        (n / msi[i].value).toFixed(1).replace(/\.?0+$/, "") +
                        msi[i].symbol
                    );
            }
            return n;
        }
    }
};
</script>
