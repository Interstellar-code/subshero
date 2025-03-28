import * as wjcCore from '@grapecity/wijmo';
import { RequiredValidator, MinNumberValidator, MinDateValidator, MaxNumberValidator, MaxDateValidator } from './validation.min.js';
//
export class KeyValue {
}
KeyValue.NotFound = { key: -1, value: '' };
//
export class Country {
}
Country.NotFound = { id: -1, name: '', flag: '' };
//
export class DataService {
    constructor(real_data) {
        this._real_data = real_data;
        this._validationConfig = {
            'payment_date': [
                new RequiredValidator(),
                new MinDateValidator(new Date('2000-01-01T00:00:00'))
            ],
            'price': [
                new RequiredValidator(),
                new MinNumberValidator(0, `Price can't be a negative value`)
            ]
        };
    }
    getPaymentMethods() {
        return this._real_data.payment_methods;
    }
    getHistoryData() {
        return this.getData();
    }
    getData() {
        return this._real_data.subscription_history;
    }
    validate(item, prop, displayName) {
        const validators = this._validationConfig[prop];
        if (wjcCore.isUndefined(validators)) {
            return '';
        }
        const value = item[prop];
        for (let i = 0; i < validators.length; i++) {
            const validationError = validators[i].validate(displayName, value);
            if (!wjcCore.isNullOrWhiteSpace(validationError)) {
                return validationError;
            }
        }
    }
}
