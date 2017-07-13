import React from 'react'
import { shallow } from 'enzyme'
import { Header } from '../app/Resources/js/components/header'

function setup() {
    const props = {
        username: 'testuser',
        currentDate: '2020-12-12'
    }

    const enzymeWrapper = shallow(<Header {...props}/>)

    return {
        props,
        enzymeWrapper
    }
}

describe('components', () => {
    describe('Header', () => {
        it('shallow smoke test', () => {
            const { enzymeWrapper } = setup()

            expect(enzymeWrapper.find('.index-header').exists()).toBe(true)
        })
    })
})
