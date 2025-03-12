<template>
  <!-- DefaultField outer -->
  <DefaultField
    :field="currentField"
    :errors="errors"
    :show-help-text="showHelpText"
    class="seo-fields form-field"
  >
    <!-- DefaultField inner -->
    <template #field>
      <div class="flex flex-col" v-bind="extraAttributes">
        <!-- Fields rows -->
        <div v-if="rows.length" class="mb-1 o1-w-full o1-flex o1-border-b o1-py-2 dark:o1-border-slate-600 flex flex-col">
          <div
            v-for="(rowField, i) in fields"
            :key="i"
            class="o1-font-bold o1-text-90 o1-text-md o1-w-full o1-ml-3 o1-flex"
            :style="{ maxWidth: rowField.nsrWidth || null }"
          >
            <!-- FieldComponent -->
            <component
              :is="`form-${rowField.component}`"
              :field="rowField"
              :errors="repeatableValidation.errors"
              :unique-id="getUniqueId(field, rowField)"
              class="o1-mr-3 w-full seo-fields-field"
              :style="{ maxWidth: rowField.nsrWidth || null }"
            />

            <!-- {{ rowField.name }}
            <span v-if="rowField.required" class="o1-text-red-500 o1-text-sm o1-pl-1">
              {{ __('*') }}
            </span> -->

            <!--  If field is nova-translatable, render separate locale-tabs   -->
            <!-- <nova-translatable-locale-tabs
              style="padding: 0"
              class="o1-ml-auto"
              v-if="rowField.component === 'translatable-field'"
              :locales="rowField.formattedLocales"
              :display-type="rowField.translatable.display_type"
              :active-locale="activeLocales[i] || rowField.formattedLocales[0].key"
              :locales-with-errors="repeatableValidation.locales[rowField.originalAttribute]"
              @tabClick="locale => setAllLocales(`sr-${field.attribute}-${rowField.originalAttribute}`, locale)"
              @doubleClick="locale => setAllLocales(void 0, locale)"
            /> -->
          </div>
        </div>

        <!-- <div
          v-for="(element, i) in rows"
          :key="i"
        >
          <div class="seo-fields-row">
            <div class="seo-fields-fields-wrapper flex flex-col">
              <component
                v-for="(rowField, j) in element"
                :key="j"
                :is="`form-${rowField.component}`"
                :field="rowField"
                :errors="repeatableValidation.errors"
                :unique-id="getUniqueId(field, rowField)"
                class="o1-mr-3"
                :style="{ maxWidth: rowField.nsrWidth || null }"
              />
            </div>
          </div>
        </div> -->

        <!-- <draggable
          v-model="rows"
          :item-key="(el, i) => (el && el[0] && el[0].attribute) || i"
          handle=".vue-draggable-handle"
        >
          <template #item="{ element, index }">
            <div class="seo-fields-row o1-flex o1-py-2 o1-pl-3 o1-relative o1-rounded-md">
              <div class="seo-fields-fields-wrapper o1-w-full o1-flex">
                <component
                  v-for="(rowField, j) in element"
                  :key="j"
                  :is="`form-${rowField.component}`"
                  :field="rowField"
                  :errors="repeatableValidation.errors"
                  :unique-id="getUniqueId(field, rowField)"
                  class="o1-mr-3"
                  :style="{ maxWidth: rowField.nsrWidth || null }"
                />
              </div>
            </div>
          </template>
        </draggable> -->

        <!-- <DefaultButton
          @click="addRow"
          class="add-button btn btn-default btn-primary"
          type="button"
        >
          {{ field.addRowLabel }}
        </DefaultButton> -->
      </div>
    </template>
  </DefaultField>
</template>

<script>
// import Draggable from 'vuedraggable';
import { Errors } from 'form-backend-validation';
import { HandlesValidationErrors, DependentFormField } from 'laravel-nova';
import HandlesRepeatable from '../mixins/HandlesRepeatable';
import _set from 'lodash/set';

export default {
  mixins: [HandlesValidationErrors, HandlesRepeatable, DependentFormField],

  // components: { Draggable },

  props: ['resourceName', 'resourceId', 'field'],

  mounted()
  {
    // eslint-disable-next-line no-console
    // console.log('[FormField.vue:66] - this.field.fields', this.field.fields);
    // console.log('[FormField.vue:66] - this.rows', this.rows);

    // this.rows = this.field.rows.map((row, rowIndex) => {
    //   // eslint-disable-next-line no-console
    //   console.log('[FormField.vue:70] - row, rowIndex', row, rowIndex);

    //   return this.copyFields(row.fields, rowIndex);
    // });

    // Listen to active locales (nova-translatable support)
    // (this.field.fields || []).forEach((field, i) => {
    //   if (field.component !== 'translatable-field') return;

    //   this.activeLocales[i] = void 0;

    //   const id = field.component === 'translatable-field' ? `sr-${this.field.attribute}-${field.attribute}` : void 0;
    //   const eventName = this.getAllLocalesEventName(id);
    //   Nova.$on(eventName, locale => {
    //     this.activeLocales = {
    //       ...this.activeLocales,
    //       [i]: locale,
    //     };
    //   });

    //   Nova.$on(this.getAllLocalesEventName(void 0), locale => {
    //     this.activeLocales = {
    //       ...this.activeLocales,
    //       [i]: locale,
    //     };
    //   });
    // });

    if (this.rows.length === 0) {
      // eslint-disable-next-line no-console
      console.log('[FormField.vue:161] - initializing first (and only row)');

      this.addRow();
    }
  },

  methods:
  {
    fill(formData) {
      const ARR_REGEX = () => /\[\d+\]$/g;

      const allValues = [];

      for (const row of this.rows) {
        let formData = new FormData();
        const rowValues = {};



        // Fill formData with field values
        row.forEach(function (field) {
          if (field === null || field === undefined) {
            // eslint-disable-next-line no-console
            console.log('[FormField.vue:178] - row / length', row, row.length);
            return;
          } else {
            field.fill(formData);
          }
        });

        // eslint-disable-next-line no-console
        console.log('[FormField.vue:185] - formData', formData);


        // Save field values to rowValues
        for (const item of formData) {
          let normalizedValue = null;

          let key = item[0];
          if (key.split('---').length === 3) {
            key = key.split('---').slice(1).join('---');
          }
          key = key.replace(/---\d+/, '');

          // Is key is an array, we need to remove the '.en' part from '.en[0]'
          const isArray = !!key.match(ARR_REGEX());
          if (isArray) {
            const result = ARR_REGEX().exec(key);
            key = `${key.slice(0, result.index)}${key.slice(result.index + result[0].length)}`;
          }

          try {
            // Attempt to parse value
            normalizedValue = JSON.parse(item[1]);
          } catch (e) {
            // Value is already a valid string
            normalizedValue = item[1];
          }

          if (isArray) {
            if (!rowValues[key]) rowValues[key] = [];
            rowValues[key].push(normalizedValue);
          } else {
            _set(rowValues, key, normalizedValue);
          }
        }

        allValues.push(rowValues);
      }

      formData.append(this.field.attribute, JSON.stringify(allValues));
    },

    addRow() {
      const fields = this.copyFields(this.field.fields, this.rows.length);
      this.rows.push(fields);
    },

    // deleteRow(index) {
    //   this.rows.splice(index, 1);
    // },
  },

  computed:
  {
    extraAttributes() {
      const attrs = this.currentField.extraAttributes;
      return {
        ...attrs,
      };
    },
    repeatableValidation() {
      const fields = this.fields;
      const errors = this.errors.errors;
      const repeaterAttr = this.field.attribute;
      const safeRepeaterAttr = this.field.attribute.replace(/.{16}__/, '');
      const erroredFieldLocales = {};
      const formattedKeyErrors = {};

      // Find errored locales
      for (const field of fields) {
        const fieldAttr = field.originalAttribute;

        // Find all errors related to this field
        const relatedErrors = Object.keys(errors).filter(
          err => !!err.match(new RegExp(`^${safeRepeaterAttr}.\\d+.${fieldAttr}`))
        );

        const isTranslatable = field.component === 'translatable-field';
        if (isTranslatable) {
          const foundLocales = relatedErrors.map(errorKey => errorKey.split('.').slice(-1)).flat();
          erroredFieldLocales[fieldAttr] = foundLocales;
        }

        // Format field
        relatedErrors.forEach(errorKey => {
          const rowIndex = errorKey.split('.')[1];
          let uniqueKey = `${repeaterAttr}---${field.originalAttribute}---${rowIndex}`;

          if (isTranslatable) {
            const locale = errorKey.split('.').slice(-1)[0];
            uniqueKey = `${uniqueKey}.${locale}`;
          }

          formattedKeyErrors[uniqueKey] = errors[errorKey];
        });
      }

      return {
        errors: new Errors(formattedKeyErrors),
        locales: erroredFieldLocales,
      };
    },
  },
};
</script>

<style lang="scss">

#nova {
  .seo-fields-field {
    margin-right: 0;
    padding-right: 0;

    > *:not(:first-child) {
      width: 100%;

      > div {
        padding-top: 0;

        > div {
          padding: 0;
        }
      }
    }

    &:not(.translatable-field) {
      padding: .4rem 0;

      > div:first-child {
        width: 20%;
      }

      > div {
        width: 80%;
        padding: 0;
      }
    }

    label,
    input,
    select,
    textarea {
      font-weight: 400;
    }

    .nova-translatable-locale-tabs {
      padding-right: 0;
    }
  }
}


.seo-fields.form-field {
  // Make field area full width
  > :nth-child(2) {
    width: 100% !important;
  }
}

// .seo-fields.form-field {
  // .seo-fields-row {
  //   width: calc(100% + 68px);

  //   > .seo-fields-fields-wrapper {
  //     .translatable-field {
  //       padding-top: 0 !important;
  //     }

  //     > *,
  //       // Improve compatibility with nova-translatable
  //     .translatable-field > div:not(:first-child) > div {
  //       flex: 1;
  //       flex-shrink: 0;
  //       min-width: 0;
  //       border: none !important;
  //       padding-top: 0 !important;
  //       padding-bottom: 0 !important;

  //       // Hide name
  //       > *:nth-child(1):not(:only-child) {
  //         display: none;
  //       }

  //       > *:only-child {
  //         > *:nth-child(1):not(:only-child) {
  //           display: none;
  //         }

  //         > :nth-child(2) {
  //           width: 100% !important;
  //           padding: 0 !important;
  //         }
  //       }

  //       // Improve compatibility with nova-compact-theme
  //       .compact-nova-field-wrapper {
  //         padding: 0 !important;
  //       }

  //       // Fix field width and padding
  //       > :nth-child(2) {
  //         width: 100% !important;
  //         padding: 0 !important;
  //       }
  //     }
  //   }
  // }


  // .add-button {
  //   width: calc(100% + 11px);

  //   &.delete-width {
  //     width: calc(100% - 22px);
  //   }
  // }

  // > :nth-child(1) {
  //   min-width: 20%;
  // }


  // Compact theme support
  // > *:only-child {
  //   > *:nth-child(1) {
  //     min-width: 20%;
  //   }

  //   // Make field area full width
  //   > *:nth-child(2) {
  //     width: 100% !important;
  //     margin-right: 24px;
  //   }
  // }
// }
</style>
