<template>
    <transition name="el-fade-in-linear">
        <!--购物车-->
        <div>
            <div class="background">

                <div v-for="(o, index) in cart" :key="index">
                    <transition name="fade" appear>
                        <div class="item round"
                             v-on:click="setSelect(index)"
                             v-bind:class="{has: curSelect[index] === 1}">
                            <!--<p>No.{{o.id}} </p>-->
                            <h1>{{goods[o.gid].name}}</h1>
                            <!--suppress HtmlUnknownTarget -->
                            <img :src="goods[o.gid].img" alt="Image">
                            <div style="height: 20px">
                                <template v-for="t in goods[o.gid].tag">
                                    <el-tag v-if="tags[t].work===true" :key="t" class="tag">
                                        {{tags[t].tag}}
                                    </el-tag>
                                </template>
                            </div>

                            <h2>数量：{{o.count}}</h2>
                            <p>价格：{{goods[o.gid].price * o.count}}</p>
                        </div>
                    </transition>
                </div>

            </div>
            <h1>总计：{{total}}</h1>
            <el-button type="primary" round :disabled="total <= 0" v-on:click="removeItem()">从购物车移除</el-button>
            <el-button type="danger" round :disabled="total <= 0" v-on:click="makeOrder()">直接购买</el-button>
        </div>
    </transition>
</template>

<script>
    import Vue from "vue";

    export default {
        name: "Cart",

        data() {
            return {
                curSelect: [],
                cart: [
                    {id: 1, gid: 1, count: 10},
                    {id: 2, gid: 2, count: 3},
                    {id: 3, gid: 3, count: 14}
                ],
            }
        },

        created() {
            fetch('http://119.3.172.223/vue/shopAPI/cart.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.cart = json.data
            }).catch(() => {
                this.$message.error('网络异常')
            })
        },

        props: {
            userId: Number,
            tags: Array,
            goods: Array,
        },

        computed: {
            total() {
                let sum = 0;
                for (let i = 0; i < this.cart.length; ++i) {
                    sum += this.curSelect[i] === 1 ? this.cart[i].count * this.goods[this.cart[i].gid].price : 0;
                }
                return sum;
            }
        },

        methods: {
            setSelect(index) {
                Vue.set(this.curSelect, index, this.curSelect[index] === 1 ? 0 : 1);
                console.log(this.total)
            },

            removeItem() {
                let url = 'http://119.3.172.223/vue/shopAPI/removeCart.php?id='
                for (let i = 0; i < this.curSelect.length; ++i) {
                    if (this.curSelect[i] === 1) {
                        fetch(url + this.cart[i].id).then(response => response.json()).then(json => {
                            if (json.errorCode !== 0) {
                                this.$message.error('系统异常，请联系管理员')
                                return
                            }
                            this.$notify({
                                title: '成功移除',
                                message: this.goods[this.cart[i].gid].name + '被移出购物车'
                            });
                            if (i === this.curSelect.length - 1) {
                                this.$emit('flush')
                                this.$message.success('移出购物车成功');
                            }
                        }).catch(() => {
                            this.$message.error('网络异常')
                        })
                    }
                }
            },

            makeOrder() {
                let url = 'http://119.3.172.223/vue/shopAPI/makerOrder.php?user=' + this.userId
                fetch(url).then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    url = 'http://119.3.172.223/vue/shopAPI/updateOrder.php?orderId=' + json.id
                    let removeUrl = 'http://119.3.172.223/vue/shopAPI/removeCart.php?id='
                    for (let i = 0; i < this.curSelect.length; ++i) {
                        if (this.curSelect[i] === 1) {
                            fetch(url + '&id=' + this.cart[i].gid + '&cnt=' + this.cart[i].count)
                                .then(response => response.json()).then(json => {
                                if (json.errorCode !== 0) {
                                    this.$message.error('系统异常，请联系管理员')
                                    return
                                }

                                fetch(removeUrl + this.cart[i].id).then(response => response.json()).then(json => {
                                    if (json.errorCode !== 0) {
                                        this.$message.error('系统异常，请联系管理员')
                                        return
                                    }
                                    this.$notify({
                                        title: '成功添加到订单',
                                        message: this.cart[i].count + '件' + this.goods[this.cart[i].gid].name + '已经加入到你的订单中'
                                    });
                                    if (i === this.curSelect.length - 1) {
                                        this.$message.success('订单创建成功，编号：' + json.id);
                                        this.$emit('flush')
                                    }
                                }).catch(() => {
                                    this.$message.error('网络异常')
                                })

                            }).catch(() => {
                                this.$message.error('网络异常')
                            })
                        }
                    }
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            }
        }
    }
</script>

<style scoped>
</style>