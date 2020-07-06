<template>
    <transition name="el-fade-in-linear">
        <!--商店-->
        <div>
            <div class="background">
                <div v-for="(o, index) in goods" :key="index">
                    <transition name="fade" appear>
                        <div class="item round" v-bind:class="{has: curSelect[index] > 0, outOff: o.inventory === 0}">
                            <!--<p>No.{{o.id}} </p>-->
                            <h1>{{decodeURIComponent(o.name)}}</h1>
                            <!--suppress HtmlUnknownTarget -->
                            <img :src="o.img" alt="暂无图片">
                            <div style="height: 20px">
                                <template v-for="t in o.tag">
                                    <el-tag v-if="tags[t - 1].work===true" :key="t" class="tag">
                                        {{tags[t - 1].tag}}
                                    </el-tag>
                                </template>
                            </div>

                            <h3 v-if="o.inventory > 0">剩余数量：{{o.inventory}}</h3>
                            <h2 v-else>无库存剩余</h2>
                            <p>价格：{{o.price}}</p>
                            <p>
                                购买：
                                <el-input-number v-model="curSelect[index]" :min="0" :max="o.inventory"
                                                 label="选购数量"></el-input-number>
                            </p>
                        </div>
                    </transition>
                </div>

            </div>

            <!--<el-button-group>-->
            <!--<el-button type="primary" icon="el-icon-arrow-left">上一页</el-button>-->
            <!--<el-button type="primary">下一页<i class="el-icon-arrow-right el-icon&#45;&#45;right"></i></el-button>-->
            <!--</el-button-group>-->

            <h1>总计：{{total}}</h1>
            <el-button type="primary" round :disabled="total <= 0" v-on:click="addToCart()">加入购物车</el-button>
            <el-button type="danger" round :disabled="total <= 0" v-on:click="makeOrder()">直接购买</el-button>
        </div>
    </transition>
</template>

<script>
    import Vue from 'vue'

    export default {
        name: "shop",

        data() {
            return {
                curSelect: [],
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
                for (let i = 0; i < this.curSelect.length; ++i) {
                    sum += this.curSelect[i] == null ? 0 : this.curSelect[i] * this.goods[i].price;
                }
                return sum.toFixed(2);
            }
        },

        methods: {
            addToCart() {
                let url = 'http://119.3.172.223/vue/shopAPI/addToCart.php?user=' + this.userId
                for (let i = 0; i < this.curSelect.length; ++i) {
                    if (this.curSelect[i] !== null && this.curSelect[i] !== 0 && this.curSelect[i] !== undefined) {
                        fetch(url + '&id=' + this.goods[i].id + '&cnt=' + this.curSelect[i])
                            .then(response => response.json()).then(json => {
                            if (json.errorCode !== 0) {
                                this.$message.error('系统异常，请联系管理员')
                                return
                            }

                            this.$notify({
                                title: '成功添加到购物车',
                                message: this.curSelect[i] + '件' + decodeURIComponent(this.goods[i].name) + '已经加入到你的购物车'
                            })
                            Vue.set(this.curSelect, i, 0)
                        }).catch(() => {
                            this.$message.error('网络异常')
                        })
                    }
                }
                this.$emit('flush')
            },

            makeOrder() {
                let url = 'http://119.3.172.223/vue/shopAPI/makerOrder.php?user=' + this.userId
                fetch(url).then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    url = 'http://119.3.172.223/vue/shopAPI/updateOrder.php?orderId=' + json.id
                    for (let i = 0; i < this.curSelect.length; ++i) {
                        if (this.curSelect[i] !== null && this.curSelect[i] !== 0 && this.curSelect[i] !== undefined) {
                            fetch(url + '&id=' + this.goods[i].id +
                                '&cnt=' + this.curSelect[i] +
                                '&cost=' + (this.curSelect[i] * this.goods[i].price))
                                .then(response => response.json()).then(json => {
                                if (json.errorCode === 1) {
                                    this.$message.error('仓储不足')
                                    return
                                } else if (json.errorCode !== 0) {
                                    this.$message.error('系统异常，请联系管理员')
                                    return
                                }
                                this.$notify({
                                    title: '成功添加到订单',
                                    message: this.curSelect[i] + '件' + decodeURIComponent(this.goods[i].name) + '已经加入到你的订单中'
                                });
                                Vue.set(this.curSelect, i, 0)
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