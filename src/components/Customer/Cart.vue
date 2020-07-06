<template>
    <transition name="el-fade-in-linear">
        <!--购物车-->
        <div>
            <div class="background" v-if="cart.length !== 0">
                <div v-for="(o, index) in cart" :key="index">
                    <transition name="fade" v-if="clock >= index" appear>
                        <div class="item round"
                             v-on:click="setSelect(index)"
                             v-bind:class="{has: curSelect[index] === 1}">
                            <!--<p>No.{{o.id}} </p>-->
                            <h1>{{decodeURIComponent(goods[o.gid - 1].name)}}</h1>
                            <!--suppress HtmlUnknownTarget -->
                            <el-image :src="goods[o.gid - 1].img" alt="暂无图片" class="good-img" lazy></el-image>
                            <div style="height: 20px">
                                <template v-for="t in goods[o.gid - 1].tag">
                                    <el-tag v-if="tags[t - 1].work===true" :key="t" class="tag">
                                        {{tags[t - 1].tag}}
                                    </el-tag>
                                </template>
                            </div>

                            <h2>数量：{{o.count}}</h2>
                            <p>价格：{{(goods[o.gid - 1].price * o.count).toFixed(2)}}</p>
                        </div>
                    </transition>
                </div>
            </div>
            <div v-else style="justify-content: center;">
                <h1 style="font-size: 50px; text-align: center;">空空如也</h1>
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
                clock: 0,
                intervalId: null,
                curSelect: [],
                cart: [],
                tags: [],
                goods: [],
            }
        },

        created() {
            fetch('http://119.3.172.223/vue/shopAPI/tags.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.tags = json.data

                fetch('http://119.3.172.223/vue/shopAPI/goods/goods.php').then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.goods = json.data

                    fetch('http://119.3.172.223/vue/shopAPI/cart.php?user=' + this.userId).then(response => response.json()).then(json => {
                        if (json.errorCode !== 0) {
                            this.$message.error('系统异常，请联系管理员')
                            return
                        }
                        this.cart = json.data

                        this.intervalId = setInterval(() => {
                            this.clock++
                            if (this.clock > this.cart.length + 1)
                                clearInterval(this.intervalId)
                        }, 200);

                    }).catch(() => {
                        this.$message.error('网络异常')
                    })

                }).catch(() => {
                    this.$message.error('网络异常')
                })

            }).catch(() => {
                this.$message.error('网络异常')
            })


        },

        props: {
            userId: Number,
        },

        computed: {
            total() {
                let sum = 0;
                for (let i = 0; i < this.cart.length; ++i) {
                    sum += this.curSelect[i] === 1 ? this.cart[i].count * this.goods[this.cart[i].gid - 1].price : 0;
                }
                return sum;
            }
        },

        methods: {
            setSelect(index) {
                Vue.set(this.curSelect, index, this.curSelect[index] === 1 ? 0 : 1);
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
                                message: decodeURIComponent(this.goods[this.cart[i].gid - 1].name) + '被移出购物车'
                            });
                        }).catch(() => {
                            this.$message.error('网络异常')
                        })
                    }
                }
                this.$emit('flush')
                this.$message.success('移出购物车成功');
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
                            fetch(url + '&id=' + this.cart[i].gid +
                                '&cnt=' + this.cart[i].count +
                                '&cost=' + (this.cart[i].count * this.goods[i].price))
                                .then(response => response.json()).then(json => {
                                if (json.errorCode === 1) {
                                    this.$message.error('仓储不足')
                                    return
                                } else if (json.errorCode !== 0) {
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
                                        message: this.cart[i].count + '件' + decodeURIComponent(this.goods[this.cart[i].gid - 1].name) + '已经加入到你的订单中'
                                    });
                                }).catch(() => {
                                    this.$message.error('网络异常')
                                })

                            }).catch(() => {
                                this.$message.error('网络异常')
                            })
                        }
                    }
                    this.$message.success('订单创建成功，编号：' + json.id);
                    this.$emit('flush')
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            }
        }
    }
</script>

<style scoped>
</style>