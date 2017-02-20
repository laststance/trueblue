import React from 'react'
import ReactTestUtils from 'react-addons-test-utils'
import assert from 'assert'
import ImportModal from '../app/Resources/js/components/import/importModal'
import ImportButton from '../app/Resources/js/components/import/importButton'

describe('testImport', () => {
    it('ModalIsRender', () => {
        const renderer = ReactTestUtils.createRenderer()
        renderer.render(<ImportModal />)
        let component = renderer.getRenderOutput()
        assert( typeof component == 'object')
    })
    
    it('ButtonIsRender', () => {
        const renderer = ReactTestUtils.createRenderer()
        renderer.render(<ImportButton />)
        let component = renderer.getRenderOutput()
        assert( typeof component == 'object')
    })
})
