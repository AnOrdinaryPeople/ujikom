import VueRouter from 'vue-router'

import { Welcome, Home, Detail, Edit, Search, Notif, MyPost, NotFound, NotifDetail } from './components'
import { Login, Register, Profile, Verified, Forgot, Reset } from './components/auth'
import { AHome, AUsers, APost, APostDetail, AReport, AMsg, ADel } from './components/admin'
import { DevHome, DevStarter, DevApp, DevDash, DevAppDocs, DevUser, DevPost, DevRep, DevAppTut, DevPhp, DevJquery, DevNode } from './components/dev'

export default new VueRouter({
    history: true,
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'welcome',
            component: Welcome
        }, {
            path: '/login',
            name: 'login',
            component: Login,
            meta: { auth: false }
        }, {
            path: '/register',
            name: 'register',
            component: Register,
            meta: { auth: false }
        }, {
            path: '/home',
            name: 'home',
            component: Home
        }, {
            path: '/profile',
            name: 'profile',
            component: Profile,
            meta: {
                auth: {
                    roles: 0,
                    redirect: '/'
                }
            }
        }, {
            path: '/home/detail/:id',
            name: 'detail',
            component: Detail
        }, {
            path: '/verify',
            name: 'verify',
            component: Verified
        }, {
            path: '/forgot',
            name: 'forgot',
            component: Forgot,
            meta: { auth: false }
        }, {
            path: '/forgot/reset',
            name: 'reset',
            component: Reset,
            meta: { auth: false }
        }, {
            path: '/home/edit/:id',
            name: 'edit',
            component: Edit,
            meta: {
                auth: {
                    roles: 0,
                    redirect: '/'
                }
            }
        }, {
            path: '/search',
            name: 'search',
            component: Search
        }, {
            path: '/notification',
            name: 'notif',
            component: Notif,
            meta: {
                auth: {
                    roles: 0,
                    redirect: '/'
                }
            }
        }, {
            path: '/notification/:id',
            name: 'notif-detail',
            component: NotifDetail,
            meta: {
                auth: {
                    roles: 0,
                    redirect: '/'
                }
            }
        }, {
            path: '/home/post',
            name: 'my-post',
            component: MyPost,
            meta: {
                auth: {
                    roles: 0,
                    redirect: '/'
                }
            }
        }, {
            path: '/admin',
            name: 'home-admin',
            component: AHome,
            meta: {
                auth: {
                    roles: 1,
                    redirect: '/home'
                }
            }
        }, {
            path: '/admin/users',
            name: 'users-admin',
            component: AUsers,
            meta: {
                auth: {
                    roles: 1,
                    redirect: '/home'
                }
            }
        }, {
            path: '/admin/posts',
            name: 'posts-admin',
            component: APost,
            meta: {
                auth: {
                    roles: 1,
                    redirect: '/home'
                }
            }
        }, {
            path: '/admin/posts/:id',
            name: 'posts-admin-detail',
            component: APostDetail,
            meta: {
                auth: {
                    roles: 1,
                    redirect: '/home'
                }
            }
        }, {
            path: '/admin/reports',
            name: 'report-admin',
            component: AReport,
            meta: {
                auth: {
                    roles: 1,
                    redirect: '/home'
                }
            }
        }, {
            path: '/admin/messages',
            name: 'message-admin',
            component: AMsg,
            meta: {
                auth: {
                    roles: 1,
                    redirect: '/home'
                }
            }
        }, {
            path: '/admin/histories',
            name: 'history-admin',
            component: ADel,
            meta: {
                auth: {
                    roles: 1,
                    redirect: '/home'
                }
            }
        }, {
            path: '/404',
            name: 'not-found',
            component: NotFound
        }, {
            path: '*',
            redirect: { name: 'not-found' }
        }, {
            path: '/developer',
            name: 'developer',
            component: DevApp,
            redirect: '/developer/home',
            children: [
                { path: 'home', component: DevHome },
                { path: 'starter', component: DevStarter },
                {
                    path: 'dashboard', component: DevDash, meta: {
                        auth: {
                            roles: 0,
                            redirect: '/developer/home'
                        }
                    }
                },
                {
                    path: 'docs', component: DevAppDocs, redirect: '/developer/docs/user', children: [
                        { path: 'user', component: DevUser },
                        { path: 'post', component: DevPost },
                        { path: 'comment', component: DevRep }
                    ]
                }, {
                    path: 'tutorial', component: DevAppTut, redirect: '/developer/tutorial/php', children: [
                        { path: 'php', component: DevPhp },
                        { path: 'jquery', component: DevJquery },
                        { path: 'nodejs', component: DevNode }
                    ]
                }
            ]
        }
    ],
})