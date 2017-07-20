import React from 'react'
import { shallow } from 'enzyme'
import { mount } from 'enzyme'
import { Header } from '../app/Resources/js/components/header'
import { Menu } from '../app/Resources/js/components/menu'
import { ImportButton } from '../app/Resources/js/components/import/importButton'
import { ImportModal } from '../app/Resources/js/components/import/importModal'
import { Timeline } from "../app/Resources/js/components/timeline";

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

    describe('Menu', () => {
        function menuSetup() {
            const props = {
                timelineJson: {},
                isLogin: true,
                currentDate: '2020-12-12',
                timelineDateList: []
            }

            const enzymeWrapper = shallow(<Menu {...props}/>)

            return enzymeWrapper
        }

        it('shallow smoke test', () => {
            const enzymeWrapper = menuSetup()

            expect(enzymeWrapper.find('#menu').exists()).toBe(true)
        })
    })

    describe('Timeline', () => {
        function timelineShalowSetup() {
            const props = {
                timelineJson: {},
                timelineDateList: [],
                currentDate: '2020-12-12',
                currentIndex: 1
            }

            // TODO https://github.com/WickyNilliams/enquire.js/issues/82
            // const enzymeWrapper = shallow(<Timeline {...props}/>)

            return enzymeWrapper
        }
        
        function timelineMountSetup() {
            const props = {
                timelineJson: {},
                timelineDateList: [],
                currentDate: '2020-12-12',
                currentIndex: 1
            }

            // TODO https://github.com/WickyNilliams/enquire.js/issues/82
            // const enzymeWrapper = mount(<Timeline {...props}/>)
        }

        it('shallow smoke test', () => {
            // const enzymeWrapper = timelineShalowSetup()

            expect(true).toBe(true)
        })

        it('should be render timeline', () => {
            // const enzymeWrapper = timelineMountSetup()

            // TODO
            expect(true).toBe(true)
        })
    })

    describe('ImportButton', () => {
        function importButtonSetup() {
            const props = {
                transText: {"import": {"btn": {"default": "valal"}}},
                isInitialImportDebug: false
            }
            const enzymeWrapper = shallow(<ImportButton {...props}/>)

            return enzymeWrapper
        }

        it('shallow smoke test', () => {
            const enzymeWrapper = importButtonSetup()

            expect(enzymeWrapper.find('Button').exists()).toBe(true)
        })
        it('collect display ButtonText', () => {
            const enzymeWrapper = importButtonSetup()

            expect(enzymeWrapper.contains(<div>valal</div>)).toBe(true)
        })
    })

    describe('ImportModal', () => {
        function importModalSetup() {
            const props = {
                transText: {"import": {"title": "title-value", "desc": "desc-value"}},
                isShowImportModal: true
            }
            const enzymeWrapper = shallow(<ImportModal {...props}/>)

            return enzymeWrapper
        }

        it('shallow smoke test', () => {
            const enzymeWrapper = importModalSetup()

            expect(enzymeWrapper.find('Modal').exists()).toBe(true)
        })
    })
})
