export default {
  data() {
    return {
      rows: [],
      activeLocales: {},
    };
  },

  mounted() {
    this.rows = this.field.rows.map((row, rowIndex) => this.copyFields(row.fields, rowIndex));

    // Listen to active locales (nova-translatable support)
    (this.field.fields || []).forEach((field, i) => {
      if (field.component !== 'translatable-field') return;

      this.activeLocales[i] = undefined;

      const id = field.component === 'translatable-field'
        ? `sr-${this.field.attribute}-${field.attribute}`
        : undefined;
      const eventName = this.getAllLocalesEventName(id);
      Nova.$on(eventName, (locale) => {
        this.activeLocales = {
          ...this.activeLocales,
          [i]: locale,
        };
      });

      Nova.$on(this.getAllLocalesEventName(undefined), (locale) => {
        this.activeLocales = {
          ...this.activeLocales,
          [i]: locale,
        };
      });
    });
  },

  methods: {
    setInitialValue() {
      // Initialize minimum amount of rows
      if (this.currentField.minRows && !isNaN(this.currentField.minRows)) {
        while (this.rows.length < this.currentField.minRows) this.addRow();
      }
    },

    copyFields(fields, rowIndex = undefined) {
      if (!rowIndex) rowIndex = this.rows.length;
      // eslint-disable-next-line no-console
      console.log('[HandlesRepeatable.js:48] - test');


      // Return an array of fields with unique attribute
      return fields.map(field => {
        const uniqueAttribute = `${this.field.attribute}---${field.attribute}---${rowIndex}`;

        let formattedLocales = null;
        if (field.component === 'translatable-field') {
          formattedLocales = this.getFieldLocales(field);
        }

        return {
          ...field,
          originalAttribute: field.attribute,
          validationKey: uniqueAttribute,
          attribute: uniqueAttribute,
          formattedLocales,
        };
      });
    },

    getFieldLocales(field) {
      let localeKeys = Object.keys(field.translatable.locales);

      if (field.translatable.prioritize_nova_locale) {
        localeKeys = localeKeys.sort((a, b) => {
          if (a === Nova.config('locale') && b !== Nova.config('locale')) return -1;
          if (a !== Nova.config('locale') && b === Nova.config('locale')) return 1;
          return 0;
        });
      }

      return localeKeys.map(key => ({ key, name: field.translatable.locales[key] }));
    },

    setAllLocales(uniqueId, newLocale) {
      Nova.$emit(this.getAllLocalesEventName(uniqueId), newLocale);
    },

    setActiveLocale(newLocale) {
      this.activeLocale = newLocale;
    },

    getAllLocalesEventName(uniqueId) {
      const id = uniqueId ?? undefined;
      return id !== undefined
        ? `nova-translatable-${id}@setAllLocale` : 'nova-translatable@setAllLocale';
    },

    getUniqueId(field, rowField) {
      const rAttribute = rowField.originalAttribute || rowField.attribute;
      return rowField.component === 'translatable-field'
        ? `sr-${field.attribute}-${rAttribute}` : undefined;
    },
  },

  computed: {
    fields() {
      return (this.rows[0] || []).filter(field => field.component !== 'hidden-field');
    },
  },
};
