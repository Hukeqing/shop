<template>
    <div class="main">
        <div>
            <h1>{{title}}</h1>
            <p>
                <el-input placeholder="请输入用户名" v-model="user" style="width: 300px"></el-input>
            </p>
            <p>
                <el-input placeholder="请输入密码" v-model="password" style="width: 300px" show-password></el-input>
            </p>
            <el-collapse-transition>
                <p v-if="!onLogin">
                    <el-input placeholder="请再次输入密码" v-model="repeatPassword" style="width: 300px"
                              show-password></el-input>
                </p>
            </el-collapse-transition>
            <el-button type="primary" v-on:click="login()" round>登录</el-button>
            <el-button type="primary" v-on:click="register()" round>注册</el-button>
        </div>
    </div>
</template>

<script>
    import {ip} from '../static/Ip'

    export default {
        name: "Login",
        props: {},
        data() {
            return {
                onLogin: true,
                user: "",
                password: "",
                repeatPassword: ""
            }
        },

        computed: {
            title() {
                return this.onLogin ? '登录' : '注册'
            }
        },

        methods: {
            login() {
                if (!this.onLogin) {
                    this.onLogin = true
                    return
                }
                // this.$emit('login', {id: 1, admin: true, user: 'Admin'})

                fetch(ip + '/login.php?user=' + this.user + '&pwd=' + this.password)
                    .then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('用户名或密码出错')
                    } else {
                        this.$emit('login', {id: json.data.id, user: json.data.user, admin: json.data.admin})
                    }
                }).catch(() => {
                    this.$message.error('网络出错')
                })
            },

            register() {
                if (this.onLogin) {
                    this.onLogin = false
                    return
                }
                // this.$emit('login', {id: 1, admin: false, user: 'User'})

                if (this.password !== this.repeatPassword) {
                    this.$message.error('两次输入的密码不相同')
                    return
                }

                if (this.password.length < 6) {
                    this.$message.error('密码过短')
                    return
                }

                fetch(ip + '/register.php?user=' + this.user + '&pwd=' + this.password)
                    .then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('用户名已经被注册')
                    } else {
                        this.$emit('login', {id: json.data.id, user: json.data.user, admin: json.data.admin})
                    }
                }).catch(() => {
                    this.$message.error('网络出错')
                })
            }
        }
    }
</script>

<style scoped>
    .main {
        margin-top: 10%;
    }
</style>