import React from 'react'
import ReactTestUtils from 'react-addons-test-utils'
import assert from 'assert'
import Timeline from '../app/Resources/js/components/timeline.jsx'

describe('testTimeline', () => {
    const getComponent = () => {
        const renderer = ReactTestUtils.createRenderer()
        renderer.render(<Timeline />)
        return renderer.getRenderOutput()
    }
    
    it('isRender', () => {
        let component = getComponent()
        assert( typeof component == 'object')
    })
})
