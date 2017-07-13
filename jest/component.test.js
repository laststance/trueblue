import React from 'react'
import { shallow } from 'enzyme'
import { Header } from '../app/Resources/js/components/header'

describe('components', () => {
    describe('Header', () => {
        function headerSetup() {
            const props = {
                username: 'testuser',
                currentDate: '2020-12-12'
            }

            const enzymeWrapper = shallow(<Header {...props}/>)

            return enzymeWrapper
        }

        it('shallow smoke test', () => {
            const enzymeWrapper = headerSetup()

            expect(enzymeWrapper.find('.index-header').exists()).toBe(true)
        })
    })
})
