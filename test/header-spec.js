import React from 'react'
import ReactTestUtils from 'react-addons-test-utils'
import assert from 'assert'
import Header from '../app/Resources/js/components/header.jsx'

describe('testHeader', () => {
    const getComponent = () => {
        const renderer = ReactTestUtils.createRenderer()
        renderer.render(<Header />)
        return renderer.getRenderOutput()
    }

    it('isRender', () => {
        let component = getComponent()
        assert( typeof component == 'object')
    })
})
