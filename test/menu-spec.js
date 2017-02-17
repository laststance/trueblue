import React from 'react'
import ReactTestUtils from 'react-addons-test-utils'
import assert from 'assert'
import Menu from '../app/Resources/js/components/menu.jsx'

describe('testMenu', () => {
    const getComponent = () => {
        const renderer = ReactTestUtils.createRenderer()
        renderer.render(<Menu />)
        return renderer.getRenderOutput()
    }
    
    it('isRender', () => {
        let component = getComponent()
        assert( typeof component == 'object')
    })
})
