import React from 'react'
import ReactTestUtils from 'react-addons-test-utils'
import assert from 'assert'
import Header from '../app/Resources/js/components/header.jsx'

describe('testHeader', () => {
    const getComponent = () => {
        return ReactTestUtils.renderIntoDocument(<Header getDailyJson={[]} timelineDateList={[]} appUsername={'hoge'}/>)
    }

    it('username', () => {
        let component = getComponent()
        assert(component.props.appUsername == 'hoge')
    })
    it('get_kaomoji', () => {
        let component = getComponent()
        assert(typeof component.get_kaomoji() == 'string')
    })
})
