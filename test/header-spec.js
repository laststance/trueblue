import React from 'react'
import ReactTestUtils from 'react-addons-test-utils'

import assert from 'assert';
import Header from '../app/Resources/js/components/header.jsx';

describe('testHeader', () => {
    it('username', () => {
        let component = ReactTestUtils.renderIntoDocument(<Header getDailyJson={[]} timeline_date_list={[]} app_user_username={'hoge'}/>)
        assert(component.props.app_user_username == 'hoge')
    });
    it('get_kaomoji', () => {
        let component = ReactTestUtils.renderIntoDocument(<Header getDailyJson={[]} timeline_date_list={[]} app_user_username={'hoge'}/>)
        assert(typeof component.get_kaomoji() == 'string')
    });
});
