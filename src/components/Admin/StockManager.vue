<template>
    <transition name="el-fade-in-linear">
        <div>
            <div class="background">
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
                <div v-for="(o, index) in goods" :key="o.id" class="card"
                     v-show="!searchMode || searchRes[index] === true">
                    <transition name="fade" v-if="clock >= o.id" appear>
                        <div class="item round" v-bind:class="{has: curUpdate[index] > 0}">
                            <!--<el-card class="round">-->
                            <!--<p>No.{{o.id}} </p>-->
                            <h1>{{decodeURIComponent(o.name)}}</h1>
                            <!--suppress HtmlUnknownTarget -->
                            <el-image :src="o.img" alt="暂无图片" class="good-img" lazy></el-image>
                            <div style="height: 20px">
                                <template v-for="t in o.tag">
                                    <el-tag v-if="tags[t - 1].work===true" :key="t" class="tag">
                                        {{decodeURIComponent(tags[t - 1].tag)}}
                                    </el-tag>
                                </template>
                            </div>

                            <h3 v-if="o.inventory > 0">剩余数量：{{o.inventory}}</h3>
                            <h2 v-else>无库存剩余</h2>
                            <!--</el-card>-->
                            <p>
                                增加：
                                <el-input-number v-model="curUpdate[index]" :min="0"
                                                 label="选购数量"></el-input-number>
                            </p>

                            <h2 v-if="curUpdate[index] > 0">增加后的库存：{{o.inventory + ((curUpdate[index] == null ||
                                curUpdate[index] === undefined) ? 0 :
                                curUpdate[index])}}</h2>
                        </div>
                    </transition>
                </div>
            </div>
            <p>
                <el-button type="primary" round v-on:click="submitUpdate">提交更新</el-button>
                <el-button type="danger" v-on:click="clearUpdate" round>清空更新</el-button>
            </p>
        </div>
    </transition>
</template>

<script>
    import {search} from "../../static/Main"
    import {ip} from '../../static/Ip'

    export default {
        name: "StockManager",

        data() {
            return {
                clock: 1,
                intervalId: null,

                searchStr: '',
                searchTag: [],
                searchRes: [],
                searchMode: false,

                tags: [],
                goods: [],
                curUpdate: []
            }
        },

        created() {
            fetch(ip + '/tags.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.tags = json.data
                fetch(ip + '/goods/goods.php').then(response => response.json()).then(json => {
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

        methods: {
            clearUpdate() {
                this.curUpdate = []
            },

            submitUpdate() {
                for (let i = 0; i < this.curUpdate.length; ++i) {
                    if (this.curUpdate[i] !== null && this.curUpdate[i] !== undefined && this.curUpdate[i] !== 0) {
                        fetch(ip + '/goods/goodAdd.php?id=' + this.goods[i].id +
                            '&cnt=' + (this.goods[i].inventory + this.curUpdate[i]))
                            .then(response => response.json()).then(json => {
                            if (json.errorCode !== 0) {
                                this.$message.error('系统异常，请联系管理员')
                            }
                            this.$emit('flush')
                        }).catch(() => {
                            this.$message.error('网络异常')
                        })
                    }
                }
            },

            search() {
                if (this.searchStr.length === 0 && this.searchTag.length === 0) {
                    this.searchMode = false
                    return
                }
                this.searchMode = true
                this.searchRes = search(this.goods, this.searchStr, this.searchTag)
            }
        }
    }
</script>

<style scoped>

</style>