import React from 'react'
import { mount } from 'enzyme'
import Header from '../app/Resources/js/components/header'

function setup() {
    const props = {
        username: 'testuser',
        currentDate: '2020-12-12'
    }

    const enzymeWrapper = mount(<Header {...props}/>)

    return {
        props,
        enzymeWrapper
    }
}

describe('components', () => {
    describe('Header', () => {
        it('should render self and subcomponents', () => {
            const { enzymeWrapper } = setup()

            expect(enzymeWrapper.find('Nabvar').hasClass('index-header').tobe(true))
        })
    })
})
