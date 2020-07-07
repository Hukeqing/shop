<template>
    <el-container>
        <el-aside width="200px">
            <el-menu :default-active="'0'" @select="changeSelect">
                <el-menu-item index="1">
                    <span slot="title">商店</span>
                </el-menu-item>
                <el-menu-item index="2">
                    <span slot="title">购物车</span>
                </el-menu-item>
                <el-menu-item index="3">
                    <span slot="title">订单</span>
                </el-menu-item>
            </el-menu>
        </el-aside>
        <el-main>
            <Shop v-if="status === 1" :user-id="userId" v-on:flush="flush"></Shop>
            <Cart v-if="status === 2" :user-id="userId" v-on:flush="flush"></Cart>
            <Order v-if="status === 3" :user-id="userId" v-on:flush="flush"></Order>
        </el-main>
    </el-container>
</template>

<script>
    import Shop from "./Customer/Shop"
    import Cart from "./Customer/Cart"
    import Order from "./Customer/Order"

    export default {
        name: "Customer",
        components: {
            Shop,
            Cart,
            Order
        },
        props: {
            userId: Number
        },

        data() {
            return {
                status: 0
            }
        },

        computed: {},

        methods: {
            changeSelect(index) {
                this.status = 0
                setTimeout(() => {
                    this.status = parseInt(index)
                }, 300);
            },

            flush() {
                let lastStatus = this.status
                this.status = 0
                setTimeout(() => {
                    this.status = parseInt(lastStatus)
                }, 300);
            }
        }
    }
</script>

<style scoped>
</style>