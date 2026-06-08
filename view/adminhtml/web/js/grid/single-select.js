define([
    'Magento_Ui/js/grid/columns/multiselect',
    'underscore',
    'uiRegistry'
], function (Select, _, registry) {
    'use strict';

    return Select.extend({
        defaults: {
            headerTmpl: 'ui/grid/columns/text',
            bodyTmpl: 'Mygento_Navigation/grid/single-select',
            label: '',
            labelField: '',
            labelTarget: '',
            extendedSelections: [],
            lastSelected: null,
            listens: {
                selected: 'onSelectedChange setExtendedSelections'
            }
        },

        /** @inheritdoc */
        initObservable: function () {
            this._super()
                .observe('extendedSelections lastSelected');

            return this;
        },

        /**
         * Getter for extended selections.
         *
         * @returns {Array}
         */
        getExtendedSelections: function () {
            return this.extendedSelections();
        },

        /**
         * Setter for extended selections.
         *
         * @param {Array} selected
         */
        setExtendedSelections: function (selected) {
            var item = {},
                extended = [];

            _.each(selected, function (id) {
                item[this.indexField] = id;
                extended.push(_.findWhere(this.rows(), item));
            }, this);

            this.set('extendedSelections', extended);
        },

        /** @inheritdoc */
        isSelected: function (id, isIndex) {
            id = this.getId(id, isIndex);

            return this.selected()[0] === id;
        },

        /** @inheritdoc **/
        select: function (id) {
            this._super();
            this.lastSelected(id);
            this.exportLabel(id);

            return this;
        },

        exportLabel: function (id) {
            if (!this.labelField || !this.labelTarget) {
                return;
            }

            const query = {
                [this.indexField]: id
            };
            const row = _.findWhere(this.rows(), query);

            if (!row) {
                return;
            }

            const label = `[ID: ${id}] ${row[this.labelField]}`;

            registry.get(this.labelTarget, function (field) {
                if (field) {
                    field.value(label);
                }
            });
        },

        /** @inheritdoc */
        _setSelection: function (id, isIndex, select) {
            var selected = this.selected;

            id = this.getId(id, isIndex);

            if (!select && this.isSelected(id)) {
                selected([]);
            } else if (select) {
                selected([id]);
            }

            return this;
        }
    });
});
