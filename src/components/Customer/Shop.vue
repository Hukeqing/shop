<template>
    <el-container>
        <el-main>
            <transition name="el-fade-in-linear">
                <!--商店-->
                <div>
                    <div class="background" v-if="goods.length !== 0">
                        <el-input
                                style="width: 47%; margin-right: 3%; margin-bottom: 30px"
                                placeholder="搜索商品关键字"
                                v-model="searchStr"
                                prefix-icon="el-icon-search"
                                v-on:input="search"
                                clearable>
                        </el-input>
                        <el-select v-model="searchTag" multiple clearable placeholder="标签过滤"
                                   style="width: 47%; margin-left: 3%; margin-bottom: 30px"
                                   v-on:change="search">
                            <template v-for="item in tags">
                                <el-option
                                        v-if="item.work"
                                        :key="item.id"
                                        :label="item.tag"
                                        :value="item.id">
                                </el-option>
                            </template>
                        </el-select>
                        <div v-for="(o, index) in goods" :key="index" class="card"
                             v-show="!searchMode || searchRes[index] === true">
                            <transition name="fade" appear v-if="clock >= index">
                                <div class="item round"
                                     v-bind:class="{has: curSelect[index] > 0, outOff: o.inventory === 0}">
                                    <!--<p>No.{{o.id}} </p>-->
                                    <h1>{{decodeURIComponent(o.name)}}</h1>
                                    <!--suppress HtmlUnknownTarget -->
                                    <el-image :src="o.img" alt="暂无图片" class="good-img" lazy></el-image>
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
                        <!--<el-button-group>-->
                        <!--<el-button type="primary" icon="el-icon-arrow-left">上一页</el-button>-->
                        <!--<el-button type="primary">下一页<i class="el-icon-arrow-right el-icon&#45;&#45;right"></i></el-button>-->
                        <!--</el-button-group>-->
                    </div>

                    <div v-else style="justify-content: center;">
                        <h1 style="font-size: 50px; text-align: center;">空空如也</h1>
                    </div>
                </div>
            </transition>
        </el-main>
        <el-footer height="100px">
            <h1>总计：{{total}}</h1>
            <el-button type="primary" round :disabled="total <= 0" v-on:click="addToCart()">加入购物车</el-button>
            <el-button type="danger" round :disabled="total <= 0" v-on:click="makeOrder()">直接购买</el-button>
        </el-footer>
    </el-container>
</template>

<script>
    import Vue from 'vue'
    import {search} from "@/static/Main";

    export default {
        name: "shop",

        data() {
            return {
                clock: 0,
                intervalId: null,

                searchStr: '',
                searchTag: [],
                searchRes: [],
                searchMode: false,

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

                    this.intervalId = setInterval(() => {
                        this.clock++
                        if (this.clock > this.goods.length + 1)
                            clearInterval(this.intervalId)
                    }, 200);

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
            },

            search() {
                if (this.searchStr.length === 0 && this.searchTag.length === 0) {
                    this.searchMode = false
                    return
                }
                this.searchMode = true
                this.searchRes = search(this.goods, this.searchStr, this.searchTag)
            }
        },
    }
</script>

<style scoped>
</style>