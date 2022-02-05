<template>
    <table class="stocks-table">
        <thead>
        <tr>
            <th v-for="col in columnNames">{{ col.replace(/_/g, ' ') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="stock in stockValues">
            <td v-for="value in stock">{{ value }}</td>
        </tr>
        </tbody>
    </table>
</template>
<script>
export default {
    data: function () {
        return {
            stockValues: Array,
            columnNames: Array,
            unusedData: [
                'id',
                'id_hash',
                'created_at',
                'updated_at',
            ]
        }
    },
    props: {
        stockPrices: [Array, null],
    },
    mounted() {
        this.stockValues = this.createStockValuesArray();
        this.columnNames = this.getColumnNames(this.stockValues);
    },
    methods: {
        getColumnNames() {
            if (null !== this.stockPrices) {
                let columnNames = Object.keys(this.stockPrices[0]);

                return columnNames.filter(item => !this.unusedData.includes(item));
            }
        },
        createStockValuesArray() {
            if (null !== this.stockPrices) {
                let stockValues = [];
                this.stockPrices.forEach(function (stock) {
                    const {id, id_hash, created_at, updated_at, ...trimmedStock} = stock;

                    stockValues.push(trimmedStock);
                })

                return stockValues;
            }

            return null;
        }
    }
}
</script>

<style scoped>
.stocks-table {
    font-family: 'Open Sans', sans-serif;
    width: 750px !important;
    border-collapse: collapse;
    border: 3px solid #44475C;
    padding-left: 30px;
    position:relative;
    margin:auto;
}

.stocks-table th {
    text-transform: uppercase;
    text-align: center;
    background: #44475C;
    color: #FFF;
    padding: 3px;
    min-width: 100px;
    border: 2px solid #7D82A8;
}

.stocks-table td {
    text-align: center;
    padding: 8px;
    border-right: 2px solid #7D82A8;
    border-bottom: 2px solid #7D82A8;
}

.stocks-table td:last-child {
    border-right: none;
}
</style>
