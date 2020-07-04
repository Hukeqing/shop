<template>
    <div class="main">
        <div>
            <h1>LOGIN</h1>
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
    import Vue from 'vue'
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-chalk/index.css'

    Vue.use(ElementUI)

    export default {
        name: "Login",
        data() {
            return {
                onLogin: true,
                user: "",
                password: "",
                repeatPassword: ""
            }
        },

        methods: {
            login() {
                if (!this.onLogin) {
                    this.onLogin = true
                    return
                }
                // TODO 登录
            },

            register() {
                if (this.onLogin) {
                    this.onLogin = false
                    return
                }
                // TODO 注册
            }
        }
    }
</script>

<style scoped>
    .main {
        margin-top: 20%;
    }

    p {
        font: 14px Base;
    }
</style>